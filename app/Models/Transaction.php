<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        "date" => "datetime",
    ];

    protected $fillable = [
        "invoice",
        "note",
        "purchase_price",
        "selling_price",
        "discount",
        "additional_cost",
        "payment_method",
        "customer_name",
        "customer_phone",
        "customer_address",
        "type_id",
        "status_id",
        "is_finished"
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, "type_id","id");
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class, "status_id","id");
    }

    public function products(): HasMany
    {
        return $this->hasMany(TransactionProduct::class, "transaction_id", "id");
    }

    public function nominals(): HasMany
    {
        return $this->hasMany(TransactionCustomNominal::class);
    }
}
