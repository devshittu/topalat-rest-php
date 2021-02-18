<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionLogResource extends JsonResource
{
    /**
     * @var mixed
     */
    private $updated_at;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'customer_email' => $this->customer_email,
            'reference' => $this->reference,
            'description' => $this->description,
            'service_category_raw' => $this->service_category_raw,
            'service_provider_raw' => $this->service_provider_raw,
            'payment_status' => $this->payment_status,
            'service_render_status' => $this->service_render_status,
            'service_request_payload_data' => $this->service_request_payload_data,
            'created_at' => Carbon::parse($this->created_at)->timestamp,
        ];
    }
}
