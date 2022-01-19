<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliverymanResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'current_longitude' => $this->current_longitude,
            'current_latitude' => $this->current_latitude,
        ];
    }
}
