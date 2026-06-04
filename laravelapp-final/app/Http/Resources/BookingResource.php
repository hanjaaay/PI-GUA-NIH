<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'tourist_attraction' => new TouristAttractionResource($this->whenLoaded('touristAttraction')),
            'visit_date' => $this->visit_date,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'order_id' => $this->order_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
