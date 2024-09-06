<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
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
            "invoice" => $this->invoice,
            "date" => $this->date->isoFormat('dddd, D MMMM Y'),
            "type" => $this->type->name,
            "status" => $this->status->name,
            "is_finished" => $this->is_finished === 1 ? true : false,
            // "purchase_price" => $this->purchase_price,
            // "selling_price" => $this->selling_price,
            "payment_method" => $this->payment_method,
            "total_price" => $this->selling_price,
            "nominals" => TransactionNominalResource::collection($this->nominals),
            "customer" => [
                "name" => $this->customer_name,
                "phone" => $this->customer_phone,
                "address" => $this->customer_address
            ],
            "products" => TransactionProductResource::collection($this->products),
            "note" => $this->note
        ];
    }
}
