<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->company_name,
            'bank' => [
                'name' => $this->bank?->name,
                'code' => $this->bank_code,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
            ],
        ];
    }
}
