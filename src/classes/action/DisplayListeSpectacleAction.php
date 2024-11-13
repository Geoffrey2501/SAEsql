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
        $html = "";
        foreach ($soiree as $key => $spectacle) {
            $id = explode(" ", $key)[1];
            $renderer = new SpectacleRenderer($spectacle);
            $html.= "<a href='?action=soiree&id=".$id."'" . "style='text-decoration: none;'>";
            $html .= $renderer->renderCompact();
            $html .= "</a>";
        }
        return $html;
    }
}