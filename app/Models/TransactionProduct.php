<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionProduct extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "name",
        "quantity",
        "price",
        "product_id",
        "option_id",
        "is_checked",
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(TransactionProductOption::class);
    }

}
