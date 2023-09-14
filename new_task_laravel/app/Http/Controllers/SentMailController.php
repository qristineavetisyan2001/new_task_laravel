<?php

namespace App\Http\Controllers;

use App\Models\SentMail;
use Illuminate\Support\Facades\DB;

class SentMailController extends Controller
{
    public static function sentMail($users, $post)
    {

        foreach ($users as $user) {
            /** @var SentMail $newRecord */
            $newRecord = new SentMail();
            $newRecord->user_id = $user->user_id;
            $newRecord->post_id = $post->id;
            $newRecord->sent = 0;

            DB::table('sent_mails')->insertOrIgnore();
        }
    }

}
