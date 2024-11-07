<?php
namespace iutnc\NRV\exception;
use Exception;
class InvalidPropertyNameException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}