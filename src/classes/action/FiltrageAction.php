<?php

namespace iutnc\NRV\action;

use iutnc\NRV\render\SpectacleRenderer;
use iutnc\NRV\repository\NRVRepository;

class FiltrageAction extends Action
{
    /**
     * @return string
     * @throws \Exception
     * Filtrer les spectacles en fonction du style, de la date et du lieu
     */

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $html = "";
            $style = filter_var($_POST['style'], FILTER_SANITIZE_NUMBER_INT);
            $date = filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS);
            $lieu = filter_var($_POST['lieu'], FILTER_SANITIZE_NUMBER_INT);

            if ($date != 0) {
                $res = NRVRepository::filtreDate($date);
            }

            if ($lieu - 1 != -1) {

                if (!isset($res) or $res === []) {
                    $res = NRVRepository::filtreLieux($lieu);
                } else {
                    $r = NRVRepository::filtreLieux($lieu);
                    foreach ($res as $key => $spectacle) {
                        {
                            if (!in_array($spectacle, $r)) {
                                unset($res[$key]);
                            }
                        }
                    }
                }
            }
            if ($style != 0) {
                if (!isset($res) or $res === []) {
                    $res = NRVRepository::filtreStyle($style);
                } else {
                    $r = NRVRepository::filtreStyle($style);
                    foreach ($res as $key => $spectacle) {
                        {
                            if (!in_array($spectacle, $r)) {
                                unset($res[$key]);
                            }
                        }
                    }
                }
            }



            if (!isset($res) or $res === []) {
                $html = "<h1>Aucun spectacle ne correspond à votre recherche</h1>";
            } else {
                foreach ($res as $spectacle) {
                $render = new SpectacleRenderer($spectacle);
                $html .= $render->render(0);
                }

            }


        } else {
            $html = "
            <form method='post'>
            <label for='style'>Style:</label>
            ";

            $choix = NRVRepository::getStyles();
            $html .= "<select id='style' name='style' required>
            <option value=0>--Sélectionnez un style de musique--</option>";
            foreach ($choix as $id => $nom) {
                $html .= "<option value=$id>$nom</option>";
            }
            $html .= "</select> ";

            $choix = NRVRepository::getDates();
            $html .= "<label for='date'>Date:</label>
            <select id='date' name='date' required>
            <option value=0>--Sélectionnez une date--</option>";
            foreach ($choix as $date) {
                $html .= "<option value=$date>$date</option>";
            }
            $html .= "</select>";

            $choix = NRVRepository::getLieux();
            $html .= "<label for='lieu'>Lieu:</label>
            <select id='lieu' name='lieu' required>
            <option value=0>--Sélectionnez un lieu--</option>";
            foreach ($choix as $id => $nom) {
                $html .= "<option value=$id>$nom</option>";
            }
            $html .= "</select> <br> <br> <button type='submit'>Filtrer</button></form>";

        }
        return $html;
    }

}