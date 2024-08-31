<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "email",
        "password",
        "name",
        "role_id"
    ];

    public function authentications(): HasMany
    {
        return $this->hasMany(Authentication::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function routeNotificationForFcm($notification)
    {
        return $this->authentications()
            ->whereNotNull("fcm_token")
            ->where("expired_at", '>', Carbon::now())
            ->pluck("fcm_token")
            ->toArray();
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
