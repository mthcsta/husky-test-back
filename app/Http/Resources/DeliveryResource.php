<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'deliveryman_id' => $this->deliveryman_id,
            'status' => $this->status,
            'status_index' => $this->getStatusIndex(),
            'collect_point_id' => $this->collect_point_id,
            'destination_point_id' => $this->destination_point_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'client' => new ClientResource($this->client),
            'deliveryman' => new DeliverymanResource($this->deliveryman),
            'collect_point' => new PointResource($this->collectPoint),
            'destination_point' => new PointResource($this->destinationPoint),
        ];
    }
}
