<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Mail\TestMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{


    public $objLabel;

    function __construct() {
        $this->objLabel = 'Message';
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|min:4|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $newResObj = new Contact();
        $newResObj->email = $request->email;
        $newResObj->subject = $request->subject;
        $newResObj->message = $request->message;
        $newResObj->full_name = $request->full_name;
        $newResObj->phone = $request->phone;

//        $newResObj->save();
        if ($newResObj->save()) {
            //send a mail from here;
            $details = [
                'title' => $request->subject,
                'body' => $request->message,
                'phone' => $request->phone,
                'email' => $request->email,
                'full_name' => $request->full_name,

            ];
            Mail::to('devshittu@gmail.com')->send(new ContactUsMail($details));

            return new \App\Http\Resources\ContactResource($newResObj);

        } else {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} can not be sent'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


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
            'full_name' => 'min:4|string',
            'email' => 'email',
            'subject' => 'string',
            'message' => 'string',
        ]);

//        Contact::whereId($id)->firstOrFail()->update($request->all());
//        $res = Contact::whereId($id)->firstOrFail();
        $obj = Contact::whereId($id)->firstOrFail();

        if (!$obj) {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($obj->update($request->all())){
            return new \App\Http\Resources\ContactResource($obj);
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
//        Contact::whereId($id)->findOrFail()->delete();

        $obj = Contact::whereId($id)->findOrFail();

        if (!$obj) {
            return response()->json([
                'success' => false,
                'message' => '{$this->objLabel} not found'
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
