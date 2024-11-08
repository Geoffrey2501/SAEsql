<?php

namespace iutnc\NRV\action;

use iutnc\NRV\repository\NRVRepository;

class AddSpectacle extends Action
{
    /**
     * @return string
     * @throws \Exception
     * Ajouter un spectacle avec un formulaire en fonction du type de requete Get ou post
     *
     */
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nameFile = filter_var($_FILES['video']['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $uploadDir = __DIR__ . '/../../../../audio/';
            $uploadFile = $uploadDir . $nameFile;
            $html='';
           if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadFile)) {
                $libelle = filter_var($_POST['libelle'], FILTER_SANITIZE_SPECIAL_CHARS);
                $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
                $style = filter_var($_POST['style'], FILTER_SANITIZE_NUMBER_INT);
                NRVRepository::addSpectacle($libelle, $titre, $uploadFile, $style);
                $html = "<h1>Le spectacle a bien été ajouté</h1>";
            }else{
                $html = "<h1>Erreur lors de l'upload de la video</h1>";
            }



        }else {
            $choix = NRVRepository::getStyles();
            $html= "<form method='post' enctype='multipart/form-data'>
                    <label for='libelle'>Libelle:</label>
                    <input type='text' id='libelle' name='libelle' required><br>
                    <label for='titre'>Titre:</label>
                    <input type='text' id='titre' name='titre' required><br>
                    <label for='video'>Fichier</label>
                    <input type='file' id='video' name='video' accept='video/*,audio/*'>
                    <label for='horaire'>Horaire:</label>
                    
                    <select id='style' name='style' required>
                    <option value=0>--Sélectionnez un style de musique--</option>";

            foreach ($choix as $id => $nom) {
                $html.= "<option value=$id>$nom</option>";
            }


            $html.= "</select> <br> <br> <button type='submit'>Ajouter</button></form>";
        }
        return $html;

    }

}