<?php

namespace iutnc\NRV\action;

use iutnc\NRV\render\SoireeRenderer;
use iutnc\NRV\repository\NRVRepository;

class DisplaySoiree extends Action
{

    /**
     * retourne le code html correspondant à l'affichage de la soiree
     * @return string
     */
    public function execute(): string
    {
        $html = "";
        if(isset($_GET['id'])){
            $soiree = NRVRepository::getInstance()->getSoiree($_GET['id']);
            $renderer = new SoireeRenderer($soiree);
            $html= $renderer->render(1);
        }
        return $html;
    }
}