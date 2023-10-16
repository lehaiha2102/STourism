<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $active_key;

    public function __construct($user)
    {
        $this->email = $user['email'];
        $this->active_key = $user['active_key'];
    }

    public function build()
    {
        return $this->from('stourism@travel.com.vn')
            ->view('verifyMail');
    }
}
