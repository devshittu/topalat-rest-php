<?php

namespace App\Http\Controllers;

use App\Models\AppPreference;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppPreferenceController extends Controller
{

    public $objLabel;

    function __construct()
    {
        $this->objLabel = 'AppPreferenceProfile';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return \App\Http\Resources\AppPreferenceResource::collection(\App\Models\AppPreference::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $obj = AppPreference::whereProfileKey($id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'success' => false,
                'message' => "{$this->objLabel} not found"
            ], Response::HTTP_NOT_FOUND);
        }

//        if (!$obj) {
//            return response()->json([
//                'success' => false,
//                'message' => "{$this->objLabel} not found"
//            ], Response::HTTP_NOT_FOUND);
//        }

        return new \App\Http\Resources\AppPreferenceResource($obj);
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

//        $request->settings = json_encode($request->settings);
        $this->validate($request, [
            'settings' => 'required|json',
        ]);
//        var_dump($request->settings);


        try {
            $obj = AppPreference::whereProfileKey($id)->firstOrFail();
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

        //        $findObj->settings = $request->settings;
        if ($obj->update($request->all())) {
            return new \App\Http\Resources\AppPreferenceResource($obj);
        } else {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} can not be updated'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
