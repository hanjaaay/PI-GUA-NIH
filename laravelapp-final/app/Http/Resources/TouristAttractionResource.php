<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TouristAttractionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'city' => $this->city,
            'province' => $this->province,
            'price' => $this->price,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'image' => $this->image,
            'featured_image' => $this->featured_image,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'average_rating' => $this->whenLoaded('reviews', function () {
                return $this->reviews->avg('rating');
            }),
            'is_active' => $this->is_active,
            'status_color' => $this->status_color,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
