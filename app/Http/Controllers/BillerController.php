<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class BillerController extends Controller
{

    /**
     * Send a brand new order for airtime purchases.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function servicesList(Request $request)
    {


        // Check if the transaction log existed fine.
        // validate input.
        // collect and process each entry
        // send the buying request if successful send
        $endpoint = '/billers/services/list';
        return $this->fetch($request, $endpoint);
    }

    /**
     * Runs order processes.
     *
     * @param $request
     * @param $endpoint
     * @return \Illuminate\Http\JsonResponse
     */
    protected function fetch($request, $endpoint = null)
    {
        // Check if the transaction log existed fine.
        // validate input.
        // collect and process each entry
        // send the buying request if successful send

        $request_body = $request->all();

        // retrieve all fields from the transaction record (service_request_payload_data) and prepare to submit for buying to avoid middleman.
//        $serviceRequestPayloadData = collect(json_decode($request_body))->toArray();
        $serviceRequestPayloadData = $request_body;

        // continue with the process of buying .
        $serviceRequestPayloadData['agentId'] = Config::get('constants.baxi.agent_id');
        $request_type = "GET";
        $json_payload = collect($serviceRequestPayloadData)->toJson();

        $signature = calculate_digest($request_type, $endpoint, $json_payload);
        $endpoint = make_baxi_url($endpoint);

        $response = Http::withHeaders([
            'X-API-KEY' => Config::get('constants.baxi.api_key'),
//            'Authorization' => 'Baxi '. Config::get('constants.baxi.username') .':'. $signature ,
            'Baxi-Date' => date(DATE_RFC1123),
        ])
            ->get($endpoint, $serviceRequestPayloadData);

        if ($response->successful()) {
            // save the request in the database and notify of the completed request.
            // update payment column for payment and service updated successfully
            return response()->json(json_decode($response->body(), true), $response->status());
        } else {
            return response()->json(json_decode($response->body(), true), $response->status());
        }


    }
}
