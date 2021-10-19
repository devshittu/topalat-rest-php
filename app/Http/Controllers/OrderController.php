<?php

namespace App\Http\Controllers;


use App\Http\Requests\OrderRequest;
use App\Http\Requests\VerifyPowerAccountRequest;
use App\Models\TransactionLog;
use App\Traits\OrderLoggerTrait;
use Carbon\Carbon;
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

    public function index()
    {
        return Http::get("reqres.in/api/users?page=1");
    }

    /**
     * Fetch account's total balance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {

        $this->middleware('order_auth');
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

            $transactionObject = TransactionLog::whereReference($agentReference)->firstOrFail();

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
    public function airtime(OrderRequest $request)
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
    public function databundle(OrderRequest $request)
    {
        $endpoint = '/api/baxipay/services/databundle/request';
        return $this->runOrder($request, $endpoint);
    }

    /**
     * Send a brand new cabletv order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function cabletv(Request $request)
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
    public function electricity(OrderRequest $request)
    {
        $endpoint = '/api/baxipay/services/electricity/request';
        return $this->runOrder($request, $endpoint);
    }


}
