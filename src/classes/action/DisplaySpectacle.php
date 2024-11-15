<?php

namespace iutnc\NRV\action;

use iutnc\NRV\render\SpectacleRenderer;
use iutnc\NRV\repository\NRVRepository;

class DisplaySpectacle extends Action
{
    /**
     * si la request est de type get alors cela retourne les contenue html contenu le spectacle
     * avec les autre recommandation par filtre
     * @return string
     */

    public function execute(): string
    {
        $html = "";
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idspec = filter_var($_GET['idspec'], FILTER_SANITIZE_NUMBER_INT);
            $idsoiree = filter_var($_GET['idsoiree'], FILTER_SANITIZE_NUMBER_INT);
            $spectacle = NRVRepository::getSpectacleById($idspec);
            $renderer = new SpectacleRenderer($spectacle);
            $html .= $renderer->render(1);
            
            $spectaclesDate = NRVRepository::filtreDate($spectacle->date);
            $html .= "<div style='margin: 10px'>";
            $html .= "<h2>Autres spectacles le même jour</h2>";
            foreach ($spectaclesDate as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $html .= $renderer->render(0);
            }
            $html .= "</div><div>";
            $idLieux = NRVRepository::getLieu($idsoiree);
            $spectaclesLieu = NRVRepository::filtreLieux($idLieux);
            $html .= "<h2>Autres spectacles au même lieu</h2>";
            foreach ($spectaclesLieu as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $html .= $renderer->render(0);
            }

            $idStyle = NRVRepository::getStyle($spectacle->style);
            $spectaclesStyle = NRVRepository::filtreStyle($idStyle);
            $html .= "</div><div>";
            $html .= "<h2>Autres spectacles du même style</h2>";
            foreach ($spectaclesStyle as $spectacle) {
                $renderer = new SpectacleRenderer($spectacle);
                $html .= $renderer->render(0);
            }
            $html .= "</div>";


        }
        return $html;
    }
}