<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCustomNominal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "transaction_id",
        "nominal_type_id",
        "amount",
    ];

    public function nominal(): BelongsTo
    {
        return $this->belongsTo(TransactionNominalType::class, "nominal_type_id", "id");
    }
}
