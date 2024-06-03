<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddProductHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "date" => $this->date->isoFormat('dddd, D MMMM Y'),
            "name" => $this->name,
            "quantity" => $this->quantity,
            "unit" => $this->unit,
            "purchase_price" => $this->purchase_price,
            "selling_price" => $this->selling_price
        ];
    }
}
