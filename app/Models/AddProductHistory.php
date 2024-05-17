<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddProductHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        "date" => "datetime",
    ];

    protected $fillable = [
        "date",
        "name",
        "quantity",
        "purchase_price",
        "selling_price"
    ];
}
