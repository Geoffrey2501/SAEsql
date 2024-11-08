<?php

namespace iutnc\NRV\action;
use iutnc\NRV\render\SpectacleRenderer;
use iutnc\NRV\repository\NRVRepository;

/**
 * Class DisplaySpectacle
 */
class DisplayListeSpectacleAction extends Action
{

    /**
     * @return string
     * @throws \Exception
     */
    public function execute(): string
    {
        $soiree = NRVRepository::getInstance()->getSpectacle();
        $html = '<div class="spectacle-list">';
        foreach ($soiree as $spectacle) {
            $renderer = new SpectacleRenderer($spectacle);
            $html .= $renderer->renderLong();
        }
        $html .= '</div>';
        return $html;
    }
}