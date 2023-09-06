<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public static function setSubscription(SubscriptionRequest $req){
        $newSub = new Subscription();

        $newSub->user_id = $req->user_id;
        $newSub->website_id = $req->website_id;

        if (Subscription::all()->where("website_id", '=', $req->website_id)->where("user_id", '=', $req->user_id)) {
            throw new Error("error");
        }

        $newSub->save();
    }


}
