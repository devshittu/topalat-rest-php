<?php


namespace App\Traits;


use App\Models\TransactionLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Component\HttpFoundation\Response;

trait OrderLoggerTrait
{
    private $objLabel = 'Transaction';

    public function findTransactionLogByRef($id, $reference = true): bool
    {
        try {
            $obj = TransactionLog::whereReference($id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return false;
        }

        if (!$obj) {
            return false;
        }
        else {
            return true;
        }


    }

    public function performRequery($requestBody)
    {

        $request_type = "GET";
        $endpoint = '/api/baxipay/superagent/transaction/requery';
        $json_payload = collect($requestBody)->toJson();
//agentReference: this.formData.reference
        $signature = calculate_digest($request_type, $endpoint, $json_payload);
        $endpoint = make_baxi_url($endpoint);

        $response = Http::withHeaders([
            'X-API-KEY' => Config::get('constants.baxi.api_key'),
//            'Authorization' => 'Baxi '. Config::get('constants.baxi.username') .':'. $signature ,
            'Baxi-Date' => date(DATE_RFC1123),
        ])
            ->get($endpoint, $requestBody);
//        dd($response->body());
        return json_decode($response->body(), true);
    }

    public function updateTransactionLogByRef($request)
    {
//        dd('$requestBody:// ', $request);

        try {
            $obj = TransactionLog::whereReference($request['agentReference'])->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return false;
//            return [
//                'success' => false,
//                'message' => "{$this->objLabel} not found"
//            ];
        }

        if (!$obj) {
            return false;
//            return [
//                'success' => false,
//                'message' => "{$this->objLabel} not found"
//            ];
        }

//        dd('Here we are', $obj);
        if ($obj->update($request)) {
            return new \App\Http\Resources\TransactionLogResource($obj);
        } else {
            return false;
//            return [
//                'success' => false,
//                'message' => "{$this->objLabel} can not be updated"
//            ];
        }

    }

    /**
     * @return string
     */
    private function agentReference($serviceCategory): string
    {
        $prefix = '';
        $prefix .= $serviceCategory ? Config::get('constants.service_categories')[$serviceCategory] : '';
//        dd(Config::get('constants.service_categories'), $prefix);
        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        return $prefix . Carbon::now()->format('YmdHis' . $micro, $t);
    }

}

