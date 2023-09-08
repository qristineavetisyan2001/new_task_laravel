<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SentMail;

class SentMailController extends Controller
{
    public static function sentMail($users, $post)
    {

        foreach ($users as $user) {
            $newRecord = new SentMail();
            $newRecord->user_id = $user->user_id;
            $newRecord->post_id = $post->id;
            $newRecord->sent = 0;

            $newRecord->save();
        }
    }

}
