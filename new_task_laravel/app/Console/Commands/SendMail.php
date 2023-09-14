<?php

namespace App\Console\Commands;

use App\Http\Controllers\SentMailController;
use App\Http\Controllers\WebsiteController;
use App\Jobs\SendNewPostNotificationsJob;
use App\Models\Post;
use App\Models\SentMail;
use App\Models\User;
use Illuminate\Console\Command;


class SendMail extends Command
{
    public User $user;
    public Post $post;
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
        $start = now()->subHour()->startOfHour();
        $end = $start->addHour();

        Post::where("notification_sent", 0)
            ->whereBetween('created_at', [$start, $end])
            ->chunk(100, function ($posts) {
                foreach ($posts as $newPost) {
                    SentMailController::sentMail(WebsiteController::getFollowers($newPost->website_id), $newPost);
                    $newPost->update(["notification_sent" => 1]);
                }
            });

        SentMail::select("sent_mails.id", "sent_mails.user_id", "sent_mails.sent", "posts.title", "posts.website_id", "posts.post_message", "users.email")
            ->with('user')
            ->with('post')
            ->where("sent_mails.sent", 0)
            ->chunk(100, function ($notSentUsers) {
                    dispatch(new SendNewPostNotificationsJob($notSentUsers));
            });

        return 200;
    }
}
