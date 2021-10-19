<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CableTVOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return false;
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
        agentReference: nowRef,
        reference: nowRef,
        agentId: this.$config.baxiAgentId,
        service_type: DEFAULT_CABLETV_PROVIDER,
        email: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_EMAIL : '',
        smartcard_number: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_POWER_ACCT_NO : '',
        total_amount: reset ? "" : 0,
        amount: reset ? "" : 0,
        phone: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_PHONE_NUMBER : '',
        product_code: reset ? "" : null,
        product_monthsPaidFor: reset ? "" : null,
        addon_code: null,
        addon_monthsPaidFor: null,
        transactionSummary: reset ? null : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_TRANSACTION_SUMMARY : '',*/
        return [
            //
            'service_type' => 'required',
            'amount' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'smartcard_number' => 'required',
            'total_amount' => 'required',
            'product_code' => 'required',
            'product_monthsPaidFor' => 'required',
//            'addon_code' => 'required',
//            'addon_monthsPaidFor' => 'required',
            'transactionSummary' => 'required',
//            '' => 'required',
//            '' => 'required',
        ];
    }
}
