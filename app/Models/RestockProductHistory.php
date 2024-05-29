<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockProductHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "category",
        "purchase_price",
        "selling_price",
        "quantity",
        "destination_address",
        "supplier_name",
        "supplier_address",
        "supplier_phone",
        "shipping_method",
        "payment_method"
    ];
}
