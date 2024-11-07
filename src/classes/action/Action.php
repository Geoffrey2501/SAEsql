<?php

/**
 * Class action
 */
abstract class Action
{
    private ?string $http_method = null;
    private ?string $hostname = null;
    private ?string $script_name = null;

    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? null;
        $this->hostname = $_SERVER['HTTP_HOST'] ?? null ;
        $this->script_name = $_SERVER['SCRIPT_NAME'] ?? null;
    }
    abstract public function execute() : string;

}