<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $active_token;

    public function __construct($email, $active_token)
    {
        $this->email = $email;
        $this->active_token = $active_token;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = [
            $email = $this->email,
            $active_token = $this->active_token,
        ];
        Mail::to($email)->send(new VerifyEmail($user));
    }
}
