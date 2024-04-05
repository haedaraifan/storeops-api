<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Authentication extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Authentication::class, "user_id", "id");
    }
}
