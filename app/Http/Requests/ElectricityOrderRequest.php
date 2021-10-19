<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ElectricityOrderRequest extends FormRequest
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
        service_type: DEFAULT_POWER_PROVIDER,
        amount: reset ? "" : DEFAULT_POWER_UNIT,
        phone: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_PHONE_NUMBER : '',
        email: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_EMAIL : '',
        account_number: reset ? "" : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_POWER_ACCT_NO : '',
        service_category_raw: SERVICE_CATEGORY_ELECTRICITY,
        transactionSummary: reset ? null : this.$config.envState === APP_ENV_VAL_DEVELOPMENT  ? DEFAULT_DUMMY_DATA_TRANSACTION_SUMMARY : '',
      */
        return [
            //
            'service_type' => 'required',
            'amount' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'account_number' => 'required',
            'service_category_raw' => 'required',
            'transactionSummary' => 'required',
        ];
    }
}
