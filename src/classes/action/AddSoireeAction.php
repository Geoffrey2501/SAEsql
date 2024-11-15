<?php
namespace iutnc\NRV\action;
use iutnc\NRV\action\Action;
use iutnc\NRV\event\Soiree;
use iutnc\NRV\repository\NRVRepository;

class AddSoireeAction extends Action{

    /**
     * retourne un questionner pour ajouter une soirée avec un GET
     * et POST pour ajouter la soirée
     * @return string
     */
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomSoiree = filter_var($_POST['nomSoiree'], FILTER_SANITIZE_SPECIAL_CHARS);
            $themeSoiree = filter_var($_POST['themeSoiree'], FILTER_SANITIZE_SPECIAL_CHARS);
            $dateSoiree = filter_var($_POST['dateSoiree'], FILTER_SANITIZE_SPECIAL_CHARS);
            $lieuSoiree = filter_var($_POST['lieu'], FILTER_SANITIZE_SPECIAL_CHARS);
            $heureSoiree = filter_var($_POST['heureSoiree'], FILTER_SANITIZE_SPECIAL_CHARS);
            $desciption = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $tarif = filter_var($_POST['tarif'], FILTER_SANITIZE_SPECIAL_CHARS);
            $soiree = new Soiree($nomSoiree, $themeSoiree, $dateSoiree, $lieuSoiree, [], $heureSoiree, $desciption, $tarif);
            $repo = NRVRepository::getInstance();
            if($repo->addSoiree($soiree)) $html = "Soirée ajoutée";
            else $html = "ce lieux est deja pris pour cette date";

        } else {
            $html = "
            <form method='post'>
            <label for='nomSoiree'>Nom de la soirée:</label>
            <input type='text' id='nomSoiree' name='nomSoiree' required>
            <label for='themeSoiree'>Thème de la soirée:</label>
            ";
            $repo = NRVRepository::getInstance();
            $choix = $repo->getThemes();
            $html .= "<select id='themeSoiree' name='themeSoiree' required>
            <option value=0>--Sélectionnez un thème--</option>";
            foreach ($choix as $id => $nom) {
                $html .= "<option value=$id>$nom</option>";
            }
            $html .= "</select>";

            $html.="<label for='dateSoiree'>Date de la soirée:</label>
            <input type='date' id='dateSoiree' name='dateSoiree' required>
            <label for='heureSoiree'>Heure de la soirée:</label>
            <input type='time' id='heureSoiree' name='heureSoiree' required>
            <label for='description'>Description de la soirée:</label>
            <input type='text' id='description' name='description' required>
            <label for='tarif'>Tarif de la soirée:</label>
            <input type='number' id='tarif' name='tarif' required>
           ";

            $choix = $repo->getLieux();
            $html .= "<label for='lieu'>Lieu:</label>
            <select id='lieu' name='lieu' required>
            <option value=0>--Sélectionnez un lieu--</option>";
            foreach ($choix as $id => $nom) {
                $html .= "<option value=$id>$nom</option>";
            }
            $html .= "</select>";

            $html.="<button type='submit'>Ajouter</button>";
        }
        return $html;
    }
}