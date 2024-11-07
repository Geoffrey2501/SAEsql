<?php

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
        return 'Test' . '<br>';
    }
}