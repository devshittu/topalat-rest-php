<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppPreferenceResource extends JsonResource
{
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
            'profile_key' => $this->profile_key,
            'settings' => json_decode($this->settings),
//            'settings' => $this->settings,
            'updated_at' => Carbon::parse($this->updated_at)->timestamp,
            'created_at' => Carbon::parse($this->created_at)->timestamp,
        ];
    }
}
