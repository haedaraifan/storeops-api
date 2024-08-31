<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->product_id,
            "name" => $this->name,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "option" => $this->option->name,
            "is_checked" => $this->is_checked === 0 ? false : true,
        ];
    }
}
