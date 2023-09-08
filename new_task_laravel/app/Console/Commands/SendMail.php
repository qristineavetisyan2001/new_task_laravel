<?php

namespace App\Console\Commands;

use App\Http\Controllers\SentMailController;
use App\Http\Controllers\WebsiteController;
use App\Jobs\SendNewPostNotificationsJob;
use App\Models\Post;
use App\Models\SentMail;
use DateTime;
use Illuminate\Console\Command;


class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Post::where("notification_sent", "=", 0)->get()->each(function ($newPost) {
            SentMailController::sentMail(WebsiteController::getFollowers($newPost->website_id), $newPost);
            $newPost["notification_sent"] = 1;
            $newPost->save();
        });

        SentMail::select("sent_mails.id", "sent_mails.user_id", "sent_mails.sent", "posts.title", "posts.website_id", "posts.post_message", "users.email")
            ->join('users', 'users.id', '=', 'sent_mails.user_id')
            ->join('posts', 'posts.id', '=', 'sent_mails.post_id')
            ->where("sent_mails.sent", "=", 0)
            ->chunk(100, function ($notSentUsers) use (&$data, &$details) {
                foreach ($notSentUsers as $user) {
                    dispatch(new SendNewPostNotificationsJob($user, ["email" => $user->email, "title" => $user->title, "post_message" => $user->post_message, "website_id" => $user->website_id]));
                }
            });

        return 200;
    }
}
