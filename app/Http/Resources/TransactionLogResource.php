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

//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'email' => $this->email,
            'reference' => $this->reference,
            'description' => $this->description,
            'service_category_raw' => $this->service_category_raw,
            'service_provider_raw' => $this->service_provider_raw,
            'payment_status' => $this->payment_status,
            'service_render_status' => $this->service_render_status,
            'service_request_payload_data' => json_decode($this->service_request_payload_data),
            'updated_at' => Carbon::parse($this->updated_at)->timestamp,
            'created_at' => Carbon::parse($this->created_at)->timestamp,
        ];
    }
}
