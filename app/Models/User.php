<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    use HasFactory;

    protected $fillable = [
        "email",
        "password",
        "name",
        "image"
    ];

    public function authentications(): HasMany
    {
        return $this->hasMany(Authentication::class, "user_id", "id");
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "product_id", "id");
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, "transaction_id", "id");
    }

    public function addProductHistories(): HasMany
    {
        return $this->hasMany(AddProductHistory::class);
    }

    public function getAuthIdentifierName()
    {
        return "email";
    }

    public function getAuthIdentifier()
    {
        return $this->email;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {

    }

    public function setRememberToken($value)
    {

    }

    public function getRememberTokenName()
    {

    }
}
