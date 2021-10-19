<?php

namespace App\Http\Controllers;

use App\Models\TransactionLog;
use App\Traits\OrderLoggerTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class TransactionLogController extends Controller
{
    use OrderLoggerTrait;

//    private $objLabel;

    function __construct() {
        $this->objLabel = 'Transaction';
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //


//        dd($request->all());

        $this->validate($request, [
            'email' => 'required|email',
//            'reference' => 'required|min:12|unique:App\Models\TransactionLog,reference',
            'description' => 'min:4',
            'service_request_payload_data' => 'required|json',
        ]);
        // TODO
        $reference  = $this->agentReference($request->service_category_raw);

        $newResObj = new TransactionLog();

        $newResObj->email = $request->email;
        $newResObj->reference = $reference;
        $newResObj->description = $request->description;
        $newResObj->service_category_raw = $request->service_category_raw;
        $newResObj->service_provider_raw = $request->service_provider_raw;
        $newResObj->payment_status = Config::get('constants.service_status.PENDING');
        $newResObj->service_render_status = Config::get('constants.service_status.PENDING');
        $newResObj->service_request_payload_data = $request->service_request_payload_data;

//        $newResObj->save();

        if ($newResObj->save()) {
            //send a mail from here;
        } else {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} can not be sent"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new \App\Http\Resources\TransactionLogResource($newResObj);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'email' => 'email',
            'reference' => 'min:12',
            'description' => 'min:4',
            'service_category_raw' => 'string',
            'service_provider_raw' => 'string',
            'payment_status' => 'integer',
            'service_render_status' => 'integer',
            'service_request_payload_data' => 'json',
        ]);
//        TransactionLog::whereReference($id)->firstOrFail()->update($request->all());
//        $res = TransactionLog::whereReference($id)->firstOrFail();
//        $obj = TransactionLog::whereReference($id)->firstOrFail();

        try {
            $obj = TransactionLog::whereReference($id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} not found"
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$obj) {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} not found"
            ], Response::HTTP_NOT_FOUND);
        }

        if ($obj->update($request->all())){
            return new \App\Http\Resources\TransactionLogResource($obj);
        } else {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} can not be updated'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        TransactionLog::whereReference($id)->firstOrFail()->delete();

        try {
            $obj = TransactionLog::whereReference($id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} not found"
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$obj) {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} not found"
            ], Response::HTTP_NOT_FOUND);
        }

        if ($obj->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'

            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} can not be deleted'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
