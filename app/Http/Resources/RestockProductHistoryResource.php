<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestockProductHistoryResource extends JsonResource
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
            "date" => $this->created_at->format("d F, Y"),
            "product" => [
                "name" => $this->name,
                "category" => $this->category,
                "pruchase_price" => $this->purchase_price,
                "selling_price" => $this->selling_price,
                "new_quantity" => $this->quantity
            ],
            "destination_address" => $this->destination_address,
            "payment_method" => $this->payment_method,
            "shipping_method" => $this->shipping_method,
            "supplier" => [
                "name" => $this->supplier_name,
                "address" => $this->supplier_address,
                "phone" => $this->supplier_phone
            ]
        ];
    }
}
