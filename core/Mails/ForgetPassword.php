<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetPassword extends Mailable {
    
    use Queueable, SerializesModels;
    
    public function __construct() {
        
    }
    
    public function build() {
        return $this->markdown('admin::mails.reset_password', ['token' => 'token']);
    }
    
}
