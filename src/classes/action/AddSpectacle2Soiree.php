<?php

namespace iutnc\NRV\action;

use iutnc\NRV\auth\Authz;
use iutnc\NRV\render\ListeSoireeRenderer;
use iutnc\NRV\render\SoireeRenderer;
use iutnc\NRV\repository\NRVRepository;

class AddSpectacle2Soiree extends Action
{
    /**
     * si la request est de type post alors cela ajoute le spectacle à la soirée
     * sinon cela retourne un formulaire pour ajouter un spectacle à une soirée
     * @return string
     */

    public function execute(): string
    {
        $html = "";
        if (Authz::checkRole("1")or Authz::checkRole("1")) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $idSoiree = filter_var($_POST['soiree'], FILTER_SANITIZE_NUMBER_INT);
                $idSpectacle = filter_var($_POST['spectacle'], FILTER_SANITIZE_NUMBER_INT);
                $horaire = filter_var($_POST['horaire'], FILTER_SANITIZE_SPECIAL_CHARS);
                $b = NRVRepository::getInstance()->addSoireeSpectacle($idSoiree, $idSpectacle, $horaire);
                if ($b) $html = "Spectacle ajouté à la soirée";
                else $html = "Erreur lors de l'ajout du spectacle à la soirée";
            } else {
                $soirees = NRVRepository::getInstance()->getSoirees();

                $html = '<form method="post" action="?action=add-spectacle2soiree">
                     <select name="soiree">';

                foreach ($soirees as $id => $soiree) {
                    $render = new SoireeRenderer($soiree);
                    // Récupérer le rendu pour chaque soirée (par exemple, un titre)
                    // Ajouter une option au menu déroulant avec l'ID en valeur
                    $html .= "<option value= $id> $soiree->nomSoiree </option>";
                }

                $html .= '</select>';

                $spectacles = NRVRepository::getInstance()->getAllTitresSpectacles();
                $html .= '<select name="spectacle">';
                foreach ($spectacles as $id => $spectacle) {
                    $html .= "<option value= $id> $spectacle</option>";
                }
                $html .= '</select>';
                $html .= '<label for="horaire">Horaire</label>';
                $html .= '<input type="time" id="horaire" name="horaire" required>';
                $html .= '<input type="submit" value="Ajouter le spectacle à la soirée">';
                $html .= '</form>';
            }
        }
        return $html;
    }
}