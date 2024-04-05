<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        "email",
        "password",
        "name"
    ];

    public function authentications(): HasMany
    {
        return $this->hasMany(Authentication::class, "user_id", "id");
    }
}
