<?php

namespace iutnc\NRV\action;

use iutnc\NRV\repository\NRVRepository;

class AddSpectacle extends Action
{
    /**
     * @return string
     * @throws \Exception
     * Ajouter un spectacle avec un formulaire en fonction du type de requete Get et utilise la methode post pour ajouter le spectacle
     *
     */
    public function execute(): string
    {
        $repo = NRVRepository::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nameFile = filter_var($_FILES['video']['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $uploadDir = realpath(__DIR__ . '/../../../../audio/') . DIRECTORY_SEPARATOR;
            $uploadFile = $uploadDir . $nameFile;

            if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadFile)) {
                $libelle = filter_var($_POST['libelle'], FILTER_SANITIZE_SPECIAL_CHARS);
                $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
                $style = filter_var($_POST['style'], FILTER_SANITIZE_NUMBER_INT);

               $repo->addSpectacle($libelle, $titre, $uploadFile, $style);


                $html = "<h1>Le spectacle a bien été ajouté</h1>";
            }

            else
            {
                $html = "<h1>Erreur lors de l'upload de la video</h1>";
            }


            $imagePath = realpath(__DIR__ . '/../../../../images/') . DIRECTORY_SEPARATOR;


            foreach ($_FILES['image']['name'] as $index => $nameImage) {
                $nameImage = filter_var($nameImage, FILTER_SANITIZE_SPECIAL_CHARS);
                $uploadFileImage = $imagePath . $nameImage;

                if (move_uploaded_file($_FILES['image']['tmp_name'][$index], $uploadFileImage)) {
                    echo $uploadFileImage;
                    $id = $repo->getIdSpectacle($libelle, $titre);
                    $repo->addImageSpectacle($id, $uploadFileImage);
                }
                else
                {
                    $html = "<h1>Erreur lors de l'upload de l'image</h1>";
                }
            }

        }else {
            $choix = $repo->getStyles();
            $html= "<form method='post' enctype='multipart/form-data'>
                    <label for='libelle'>Libelle:</label>
                    <input type='text' id='libelle' name='libelle' required><br>
                    <label for='titre'>Titre:</label>
                    <input type='text' id='titre' name='titre' required><br>
                    <label for='video'>Fichier</label>
                    <input type='file' id='video' name='video' accept='video/*,audio/*'>
                    
                    
                    <label for='image'>Image</label>
                    <input type='file' id='image' name='image[]' accept='image/*' multiple>
                        
                  
                    <label for='style'>Style:</label>
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