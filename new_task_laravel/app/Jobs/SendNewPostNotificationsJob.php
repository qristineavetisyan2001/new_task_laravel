<?php

namespace App\Jobs;

use App\Console\Commands\SendMail;
use App\Mail\MailNotify;
use App\Models\SentMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewPostNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    private $notSentUsers;

    public function __construct($notSentUsers)
    {
        $this->notSentUsers = $notSentUsers;
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        foreach($this->notSentUsers as $notSentUser) {
            /** @var SentMail $notSentUser */
            $user = $notSentUser->user;
            $post = $notSentUser->post;

            Mail::to($user->email)->send(new MailNotify([
                "website_id" => $post->website_id,
                "title" => $post->title,
                "post_message" => $post->post_message
            ]));

            $notSentUser->sent = 1;
            $notSentUser->save();
        }
    }
}
