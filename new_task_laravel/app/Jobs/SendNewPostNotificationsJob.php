<?php

namespace App\Jobs;

use App\Mail\MailNotify;
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

    private $notSentUser;
    private $details;

    public function __construct($notSentUser, $details)
    {
        $this->notSentUser = $notSentUser;
        $this->details = $details;
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        Mail::to($this->details["email"])->send(new MailNotify($this->details));

        $this->notSentUser->sent = 1;
        $this->notSentUser->save();
    }
}
