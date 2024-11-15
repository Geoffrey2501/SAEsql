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
     * retourne le code html correspondant à la requête
     * @return string
     * @throws \Exception
     */
    public function execute(): string
    {
        $soiree = NRVRepository::getInstance()->getSpectacle();
        $html = "";
        foreach ($soiree as $key => $spectacle) {
            $renderer = new SpectacleRenderer($spectacle);
            $html .= $renderer->renderCompact();

        }
        return $html;
    }
}