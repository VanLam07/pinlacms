<?php

namespace App\Exceptions;

use Exception;

class PlException extends Exception
{
    
    protected $error;

    public function __construct($error, $code = 0, $previous = null) {
        $this->error = $error;
        parent::__construct(is_string($error) ? $error : '', $code, $previous);
    }
    
    public function getError(){
        $message = $this->error;
        if (is_object($message)) {
            $htmlError = '<ul>';
            foreach ($message->all() as $mess) {
                $htmlError .= '<li>'. $mess .'</li>';
            }
            $htmlError .= '</ul>';
            return $htmlError;
        }
        
        if (config('app.env') == 'production') {
            return 'Error system';
        }
        return $message;
    }
    
}

