<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => $this->date,
            "type" => $this->type->name,
            "status" => $this->status->name,
            "purchase_price" => $this->purchase_price,
            "selling_price" => $this->selling_price
        ];
    }
}
