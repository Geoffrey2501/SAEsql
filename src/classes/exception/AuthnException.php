<?php

namespace iutnc\NRV\exception;
use Exception;

class AuthnException extends Exception
{

    /**
     * @param string $string
     */
    public function __construct($message = "authentification failed", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}