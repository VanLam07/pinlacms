<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class PlException extends Exception
{
    
    protected $error;

    public function __construct($error, $code = 0, $previous = null) {
        $this->error = $error;
        parent::__construct($message, $code, $previous);
    }
    
    public function getMessage(){
        $message = $this->error;
        if ($message instanceof MessageBag) {
            $htmlError = '<ul>';
            foreach ($message as $arrMess) {
                foreach ($arrMess as $mess) {
                    $htmlError .= '<li>'. $mess .'</li>';
                }
            }
            $htmlError = '</ul>';
            $messsage = $htmlError;
        }
        return $message;
    }
    
}

