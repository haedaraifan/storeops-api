<?php

namespace App\Helpers;
use App\Models\User;
use App\Notifications\MobileAppNotification;
use Carbon\Carbon;

class SendNotificationHelper
{
    public static function toMobileApp(string $title, string $body)
    {
        $users = User::whereHas("authentications", function ($query) {
            $query->whereNotNull("fcm_token")
                  ->where("expired_at", '>', Carbon::now());
        })->get();

        foreach ($users as $user) {
            $user->notify(new MobileAppNotification($title, $body));
        }
    }
}
