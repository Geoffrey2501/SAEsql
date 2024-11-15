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
            $repo = NRVRepository::getInstance();
            $idspec = filter_var($_GET['idspec'], FILTER_SANITIZE_NUMBER_INT);
            $idsoiree = filter_var($_GET['idsoiree'], FILTER_SANITIZE_NUMBER_INT);
            $spectacle = $repo->getSpectacleById($idspec);
            $renderer = new SpectacleRenderer($spectacle);
            $html .= $renderer->render(1);
            
            $spectaclesDate = $repo->filtreDate($spectacle->date);
            $html .= "<div style='margin: 10px'>";
            $html .= "<h2>Autres spectacles le même jour</h2>";
            foreach ($spectaclesDate as $spectacle1) {
                $renderer = new SpectacleRenderer($spectacle1);
                $html .= $renderer->render(0);
            }
            $html .= "</div><div>";
            $idLieux = $repo->getLieu($idsoiree);
            $spectaclesLieu = $repo->filtreLieux($idLieux);
            $html .= "<h2>Autres spectacles au même lieu</h2>";
            foreach ($spectaclesLieu as $spectacle2) {
                $renderer = new SpectacleRenderer($spectacle2);
                $html .= $renderer->render(0);
                $html.="</a>";
            }

            $idStyle = $repo->getStyle($spectacle->style);
            $spectaclesStyle = $repo->filtreStyle($idStyle);
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