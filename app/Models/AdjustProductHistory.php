<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustProductHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        "date" => "datetime",
    ];

    protected $fillable = [
        "date",
        "product_id",
        "name",
        "category",
        "quantity",
        "message"
    ];
}
