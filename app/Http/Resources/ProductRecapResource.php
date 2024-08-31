<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRecapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this["id"],
            "name" => $this["name"],
            "category" => $this["category"],
            "first_quantity" => (int) ($this["first_quantity"] ?? 0),
            "last_quantity" => (int) ($this["last_quantity"] ?? 0),
            "incoming_quantity" => (int) ($this["incoming_quantity"] ?? 0),
            "outgoing_quantity" => (int) ($this["outgoing_quantity"] ?? 0),
        ];
    }
}
