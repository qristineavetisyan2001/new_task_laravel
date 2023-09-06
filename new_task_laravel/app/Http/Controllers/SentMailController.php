<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SentMail;

class SentMailController extends Controller
{
    public static function sentMail($users, $dateTime)
    {

        foreach ($users as $user) {
            $newRecord = new SentMail();
            $newRecord->user_id = $user->user_id;
            $newRecord->post_id = Post::where("post_date_time", "=", $dateTime)->get()->first()->id;
            $newRecord->sent = 0;

            $newRecord->save();
        }
    }

}
