<?php

namespace iutnc\NRV\action;

/**
 * Class DefaultAction
 */
class DefaultAction extends Action
{
    /**
     * @return string
     */
    public function execute(): string
    {
        return "Bienvenue sur le site de la NRV";
    }
}