<?php

namespace App\Http\Controllers;


use App\Http\Requests\OrderRequest;
use App\Http\Requests\VerifyPowerAccountRequest;
use App\Models\TransactionLog;
use App\Traits\OrderLoggerTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Support\Facades\Config;

// Added this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use OrderLoggerTrait;

    private $refPrefixAirtime = 'AT';
    private $refPrefixDatabundles = 'DT';
    private $refPrefixCabletv = 'CT';
    private $refPrefixElectricity = 'EL';


    /**
     * Fetch account's total balance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {

//        $this->middleware('order_auth');
        $getCurrentTimeRFC = date(DateTime::RFC2822);
//        $getCurrentTimeRFC = "Thu, 27 Jan 2022 14:21:25 +0100";
        $getCurrentTimeRFC = "Fri, 28 Jan 2022 00:37:09 +0100";
//        var_dump($getCurrentTimeRFC);
        $toutc = strtotime($getCurrentTimeRFC);
        $reversedDate = date(DATE_RFC2822,$toutc);
//        dd($getCurrentTimeRFC, $toutc, $reversedDate);
        $request_type = "GET";
        $endpoint = "/api/baxipay/superagent/account/balance";
        $json_payload = '';

        $signature = calculate_digest($request_type, $endpoint, $json_payload);
        $endpoint = make_baxi_url($endpoint);
        $response = Http::withHeaders([
//            'X-API-KEY' => Config::get('constants.baxi.api_key'),
            'Authorization' => 'Baxi ' . Config::get('constants.baxi.username') . ':' . $signature,
            'Baxi-Date' => date(DATE_RFC1123),
        ])
            ->get($endpoint);

        return response()->json(json_decode($response->body(), true), $response->status());
    }


    /**
     * Verify account info on electricity bill.
     *
     * @param \App\Http\Requests\VerifyPowerAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyAccount(VerifyPowerAccountRequest $request)
    {

        $request_type = "POST";
        $endpoint = '/api/baxipay/services/namefinder/query';
//        $json_payload = '';
        $json_payload = collect($request)->toJson();
//        dd($request, $json_payload);


        $signature = calculate_digest($request_type, $endpoint, $json_payload);
        $endpoint = make_baxi_url($endpoint);
        $response = Http::withHeaders([
            'X-API-KEY' => Config::get('constants.baxi.api_key'),
//            'Authorization' => 'Baxi ' . Config::get('constants.baxi.username') . ':' . $signature,
            'Baxi-Date' => date(DATE_RFC1123),
        ])
            ->post($endpoint, collect($request)->toArray());

        return response()->json(json_decode($response->body(), true), $response->status());
    }


    /**
     * Runs order processes.
     *
     * @param $request
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse
     */
    protected function runOrder($request, $endpoint=null)
    {


        // Check if the transaction log existed fine.
        // validate input.
        // collect and process each entry
        // send the buying request if successful send

        $request_body = $request->all();

        $agentReference = $request_body['agentReference'];
        $getTransactionLogByRef = $this->findTransactionLogByRef($agentReference);
        if ($getTransactionLogByRef) {

            try {
                $transactionObject = TransactionLog::whereReference($agentReference)->firstOrFail();
            } catch (ModelNotFoundException $ex) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Transaction not found"
                ], Response::HTTP_NOT_FOUND);
            }
            // check payment successfully made.
            if(!$transactionObject->payment_status) {

                return response()->json([
                    'status' => 'error',
                    'message' => "{$this->objLabel} payment not completed/confirmed. Please reach us through our help lines. \n REF: $agentReference"
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // retrieve all fields from the transaction record (service_request_payload_data) and prepare to submit for buying to avoid middleman.
            $serviceRequestPayloadData  = collect(json_decode($transactionObject->service_request_payload_data))->toArray();

            // continue with the process of buying .
            $serviceRequestPayloadData['agentReference'] = $agentReference;
            $serviceRequestPayloadData['reference'] = $agentReference;
            $serviceRequestPayloadData['agentId'] = Config::get('constants.baxi.agent_id');


            $request_type = "POST";
//            $endpoint = '/api/baxipay/services/airtime/request';
            $json_payload = collect($serviceRequestPayloadData)->toJson();

            $signature = calculate_digest($request_type, $endpoint, $json_payload);
            $endpoint = make_baxi_url($endpoint);

            $response = Http::withHeaders([
                'X-API-KEY' => Config::get('constants.baxi.api_key'),
//            'Authorization' => 'Baxi '. Config::get('constants.baxi.username') .':'. $signature ,
                'Baxi-Date' => date(DATE_RFC1123),
            ])
                ->post($endpoint, $serviceRequestPayloadData);

            if ($response->successful()) {
                // save the request in the database and notify of the completed request.
                // update payment column for payment and service updated successfully
//
                $updateTransactionPayloadData['service_render_status'] = Config::get('constants.service_status.COMPLETED');
                $updateTransactionPayloadData['agentReference'] = $agentReference;

                // perform requery
                $requery = $this->performRequery(['agentReference' => $agentReference]);

                if ($requery['status'] === "success")
                {
                    $this->updateTransactionLogByRef($updateTransactionPayloadData);
                }

                return response()->json(json_decode($response->body(), true), $response->status());
            }
            if ($response->failed()) {

                // perform requery
                $requery = $this->performRequery(['agentReference' => $agentReference]);

                if ($requery['status'] !== "success" || json_decode($response->body(), true)['code'] !== 'BX0023'){
                    $request_body['service_render_status'] = Config::get('constants.service_status.FAILED');
                }

                return response()->json(json_decode($response->body(), true), $response->status());
            }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => "{$this->objLabel} can not be found \n REF: $agentReference",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Send a brand new order for airtime purchases.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retryAirtime(OrderRequest $request)
    {


        // Check if the transaction log existed fine.
        // validate input.
        // collect and process each entry
        // send the buying request if successful send
        $endpoint = '/api/baxipay/services/airtime/request';
        return $this->runOrder($request, $endpoint);
    }

    /**
     * Send a brand new order for data subscription.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retryDatabundle(OrderRequest $request)
    {
        $endpoint = '/api/baxipay/services/databundle/request';
        return $this->runOrder($request, $endpoint);
    }

    /**
     * Send a brand new cabletv order.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function retryCabletv(OrderRequest $request)
    {
        $endpoint = '/api/baxipay/services/multichoice/request';
        return $this->runOrder($request, $endpoint);
    }

    /**
     * Send a brand new electricity order.
     *
     * @param \Illuminate\Http\OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retryElectricity(OrderRequest $request)
    {
        $endpoint = '/api/baxipay/services/electricity/request';
        return $this->runOrder($request, $endpoint);
    }




    /**
     * Runs order processes.
     *
     * @param $request
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse
     */
    protected function retryOrder($request, $endpoint=null)
    {


        // Check if the transaction log existed fine.
        // validate input.
        // collect and process each entry
        // send the buying request if successful send

        $request_body = $request->all();

        $agentReference = $request_body['agentReference'];
        $getTransactionLogByRef = $this->findTransactionLogByRef($agentReference);
        if (!$getTransactionLogByRef) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Old transaction with ref: {$agentReference} not found"
                ], Response::HTTP_NOT_FOUND);
        } else {

            try {
                $transactionObject = TransactionLog::whereReference($agentReference)->firstOrFail();
            } catch (ModelNotFoundException $ex) {}
            // check payment successfully made.
            if(!$transactionObject->payment_status) {

                return response()->json([
                    'status' => 'error',
                    'message' => "{$this->objLabel} payment not completed/confirmed. Please reach us through our help lines. \n REF: $agentReference"
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            dd('$transactionObject', $transactionObject);
            // TODO: create a new reference with transaction log append the transaction log reference with 'RE:'

            $newReference  = $this->agentReference($request->service_category_raw);

            $newResObj = new TransactionLog();

            $newResObj->email = $request->email;
            $newResObj->parent_reference = $agentReference;
            $newResObj->reference = $newReference;
            $newResObj->description = $request->description;
            $newResObj->service_category_raw = $request->service_category_raw;
            $newResObj->service_provider_raw = $request->service_provider_raw;
            $newResObj->payment_status = Config::get('constants.service_status.COMPLETED');
            $newResObj->service_render_status = Config::get('constants.service_status.PENDING');
            $newResObj->service_request_payload_data = $request->service_request_payload_data;

            if ($newResObj->save()) {
                //send a mail from here;
            }

            // retrieve all fields from the transaction record (service_request_payload_data) and prepare to submit for buying to avoid middleman.
            $serviceRequestPayloadData  = collect(json_decode($transactionObject->service_request_payload_data))->toArray();

            // continue with the process of buying .
            $serviceRequestPayloadData['agentReference'] = $newReference;
            $serviceRequestPayloadData['reference'] = $newReference;
            $serviceRequestPayloadData['agentId'] = Config::get('constants.baxi.agent_id');


            $request_type = "POST";
//            $endpoint = '/api/baxipay/services/airtime/request';
            $json_payload = collect($serviceRequestPayloadData)->toJson();

            $signature = calculate_digest($request_type, $endpoint, $json_payload);
            $endpoint = make_baxi_url($endpoint);

            $response = Http::withHeaders([
                'X-API-KEY' => Config::get('constants.baxi.api_key'),
//            'Authorization' => 'Baxi '. Config::get('constants.baxi.username') .':'. $signature ,
                'Baxi-Date' => date(DATE_RFC1123),
            ])
                ->post($endpoint, $serviceRequestPayloadData);

            if ($response->successful()) {
                // save the request in the database and notify of the completed request.
                // update payment column for payment and service updated successfully
//
                $updateTransactionPayloadData['service_render_status'] = Config::get('constants.service_status.COMPLETED');
                $updateTransactionPayloadData['agentReference'] = $newReference;

                // perform requery
                $requery = $this->performRequery(['agentReference' => $newReference]);

                if ($requery['status'] === "success")
                {
                    $this->updateTransactionLogByRef($updateTransactionPayloadData);
                }
                return response()->json(json_decode($response->body(), true), $response->status());
            }
            if ($response->failed()) {

                // perform requery
                $requery = $this->performRequery(['agentReference' => $newReference]);

                if ($requery['status'] !== "success" || json_decode($response->body(), true)['code'] !== 'BX0023'){
                    $request_body['service_render_status'] = Config::get('constants.service_status.FAILED');
                }

                return response()->json(json_decode($response->body(), true), $response->status());
            }

        }


    }


}
