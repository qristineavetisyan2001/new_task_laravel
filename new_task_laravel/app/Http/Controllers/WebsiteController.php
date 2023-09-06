<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public static function getFollowers($website_id)
    {
        $followers = User::join('subscription', 'subscription.user_id', '=', 'users.id')
            ->join('posts', 'posts.website_id', '=', 'subscription.website_id')
            ->where('subscription.website_id', '=', $website_id)->where("posts.created_at", ">", "subscription.created_at")
            ->get();

        return $followers;
    }
}
