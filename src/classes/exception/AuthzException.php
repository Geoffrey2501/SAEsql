<?php

namespace iutnc\NRV\exception;
use Exception;
class AuthzException extends Exception
{
    public function __construct($message = "permission denied", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}