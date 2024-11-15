<?php
declare(strict_types=1);

namespace iutnc\NRV\render;
use iutnc\NRV\event\Spectacle;
use iutnc\NRV\repository\NRVRepository;


class SpectacleRenderer extends EventRenderer {
    /**
     * @var Spectacle
     */
    protected Spectacle $spectacle;
    /**
     * @var string
     */
    private string $style;


    public function __construct(Spectacle $spec)
    {
        $this->spectacle = $spec;
        $this->style = "<style>
.spectacle-card {
    min-width: 240px;
    min-height: 300px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    max-width: 400px;
    margin: 10px;
    font-family: Arial, sans-serif;
}

.spectacle-cardL {
    width: 80%; /* Utilise 80% de la largeur disponible */
    max-width: 800px; /* Augmente la largeur maximale pour le renderLong */
    min-height: 400px; /* Hauteur minimum agrandie */
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin: 20px auto; /* Centrer la carte et augmenter l'espacement */
    padding: 20px; /* Ajoute du padding pour plus d'espace autour du contenu */
    font-family: Arial, sans-serif;
}

img {
    width: 120px; /* Taille de largeur maximum */
    height: auto; /* Hauteur ajustée pour maintenir les proportions */
    display: block;
    margin: 10px auto; /* Centrage horizontal */
}

.spectacle-titre {
    font-size: 1.8em;
    color: cornflowerblue;
    text-align: center;
    padding: 15px;
    margin: 0;
}

.spectacle-details {
    list-style-type: none;
    padding: 15px;
    margin: 0;
}

.spectacle-details li {
    margin: 10px 0;
    color: #555;
}

.spectacle-images {
     display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); /* Chaque image occupe une colonne de 100px minimum */
    gap: 10px; /* Espace entre les images */
    justify-items: center;
}

.spectacle-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 2px solid #333;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.spectacle-image2 {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 2px solid #333;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>";
    }
    /**
     * affiche les spectacles de manière compacte
     * @param int $selector
     * @return string
     */
    public function renderCompact(): string {
        $img = "";
        if (isset($this->spectacle->images[0])) {
            $img = $this->spectacle->images[0];
        }
        $repo = NRVRepository::getInstance();
        $i = $repo->getIdSpectacle($this->spectacle->description, $this->spectacle->titre);
        $i2 = $repo->getIdSoiree($this->spectacle->date, $i);
        return $this->style."
    <a href='?action=soiree&id=".$i2."'>
        <div class='spectacle-card'>
            <div class='spectacles-image'>
                <img src='./../images/".$img."' class='spectacle-image2' alt='Image du spectacle'>
            </div>
            <h2 class='spectacle-titre'>".$this->spectacle->titre."</h2>
            <ul class='spectacle-details'>
                <li class='spectacle-time'><strong>Date : </strong> ".$this->spectacle->date."</li>
                <li class='spectacle-horaire'><strong>Horaire : </strong>".$this->spectacle->horairePrevisionnel."</li>
                <li class='spectacle-description'><strong>Description : </strong>".$this->spectacle->description."</li>
            </ul>
        </div>
    </a>
        ";
    }
    /**
     * affiche les spectacles de manière longue
     * @return string
     */
    public function renderLong(): string {
        $repo = NRVRepository::getInstance();
        $i = $repo->getIdSpectacle($this->spectacle->description, $this->spectacle->titre);

        $html= $this->style."<a href='?action=display-spectacle-filtre&idspec=".$i."&idsoiree=".$repo->getIdSoiree($this->spectacle->date, $i)."'><div class='spectacle-cardL'><h2 class='spectacle-titre'>" . $this->spectacle->titre . "</h2>
                        <ul class='spectacle-details'>
                        <li class='spectacle-horaire'><strong>Heure de début : </strong>" . $this->spectacle->horairePrevisionnel . "</li>
                        <li class='spectacle-description'><strong>Description : </strong>" . $this->spectacle->description . "</li>
                        <li><strong>Style : </strong> " . $this->spectacle->style . "</li>
                        <li><strong>Artistes : </strong> <ul>" . $this->renderArtistes() . "</ul></li>
                        <li><strong>Aperçus : </strong> " . $this->renderImage() . "</li>";
        if($this->spectacle->extrait != null) {
            if(str_contains($this->spectacle->extrait, ".mp4"))$html .= "<li><strong>Video:</strong> <video controls><source src='./../audio/" . $this->spectacle->extrait . "' type='video/mp4'></video></li>";
            else $html .= "<li><strong>Video:</strong> <audio controls><source src='" . $this->spectacle->extrait . "' type='audio/mp3'></audio></li>";
        }
        $html.=" </ul></div></a>";
        return $html;
    }
    /**
     * affiche les artistes du spectacle
     * @return string
     */
    public function renderArtistes(): string {
        $html = "<ul>";
        $artistes = $this->spectacle->artistes;
        if (empty($artistes)) {
            return '';
        }


        foreach($artistes as $artiste) {
            $html .= "<li>" . $artiste . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
    /**
     * affiche les images du spectacle
     * @return string
     */
    public function renderImage(): string {
        $html = " <div class='spectacle-images'>";
        foreach($this->spectacle->images as $image) {
            $html .= "<img src='./../images/$image' class='spectacle-image' alt='Image du spectacle'>";
        }

        return $html."</div>";
    }
}