<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsRecap extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        "date" => "datetime",
    ];

    protected $fillable = [
        "date",
        "name",
        "image",
        "first_quantity",
        "last_quantity",
        "incoming_quantity",
        "outgoing_quantity"
    ];
}
