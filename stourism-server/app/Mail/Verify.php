<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verify extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $full_name;
    public $token;

    public function __construct($user)
    {
        $this->email = $user['email'];
        $this->full_name = $user['full_name'];
        $this->token = $user['active_key'];
    }

    public function build()
    {
        return $this->from('stourism@travel.com', 'S Tourism')
            ->view('emails.mail', [
                'user' => [
                    'email' => $this->email,
                    'full_name' => $this->full_name,
                    'token' => $this->token
                ]
            ]);
    }
}
