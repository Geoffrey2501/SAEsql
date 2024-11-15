<?php

namespace iutnc\NRV\action;

/**
 * Class action
 */
abstract class Action
{
    /**
     * retourne le code html correspondant à la requête
     * @return string
     */
    abstract public function execute() : string;

}