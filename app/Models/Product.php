<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "quantity",
        "purchase_price",
        "selling_price",
        "image",
        "category",
        "unit_id"
    ];

    protected $dates = [
        "deleted_at"
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, "unit_id", "id");
    }
}
