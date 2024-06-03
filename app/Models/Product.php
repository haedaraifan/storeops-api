<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "quantity",
        "purchase_price",
        "selling_price",
        "image",
        "unit_id"
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, "unit_id", "id");
    }
}
