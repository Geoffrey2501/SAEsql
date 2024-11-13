<?php
declare(strict_types=1);

namespace iutnc\NRV\render;

use iutnc\NRV\event\Soiree;

class SoireeRenderer extends EventRenderer {
    protected Soiree $soiree;

    private string $style;

    public function __construct(Soiree $soiree) {
        $this->soiree = $soiree;
        $this->style = "<style>
.soiree-container {
    width: 350px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    color: #333;
}

.soiree-header {
    background-color: #3b5998; /* Couleur d'accent */
    color: #ffffff;
    padding: 15px;
    border-radius: 8px 8px 0 0;
    text-align: center;
}

.soiree-header h2 {
    margin: 0;
    font-size: 1.8em;
}

.soiree-details, .soiree-tarif {
    padding: 15px;
    margin-top: 10px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.soiree-details ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.soiree-details li {
    margin: 10px 0;
}

.soiree-details li strong {
    color: #555;
}

.soiree-tarif {
    text-align: center;
    font-size: 1.2em;
    color: #4caf50; /* Couleur pour mettre en valeur le tarif */
}
</style>";
    }

    public function renderCompact(): string {
        return "<div style='text-align: center; padding: 10px 0;'>
                    <h2>Soiree: " . $this->soiree->nomSoiree . "</h2>
                        <ul style='list-style-type: none; padding: 0;'>      
                            <li><strong>Thème:</strong>" . $this->soiree->themeSoiree . "</li>
                            <li><strong>Date:</strong>" . $this->soiree->dateSoiree . "</li>
                            <li><strong>Lieu:</strong>" . $this->soiree->lieuSoiree . "</li>
                            <li><strong>Heure:</strong>" . $this->soiree->heureSoiree . "</li>
                        </ul>
                </div>";
    }

    public function renderLong() : string
    {
        return "<div class='soiree-container'>
                <div class='soiree-header'>
                    <h2>" . $this->soiree->nomSoiree . "</h2>
                </div>
                <div class='soiree-details'>
                    <ul>
                        <li><strong>Thème:</strong> " . $this->soiree->themeSoiree . "</li>
                        <li><strong>Date:</strong> " . $this->soiree->dateSoiree . "</li>
                        <li><strong>Lieu:</strong> " . $this->soiree->lieuSoiree . "</li>
                        <li><strong>Heure:</strong> " . $this->soiree->heureSoiree . "</li>
                    </ul>
                </div>
                <div class='soiree-tarif'>
                    <strong>Tarif: " . $this->soiree->tarif . "€/personne</strong>
                </div>
            </div>" . $this->renderSpectacles();
    }

    public function renderSpectacles(): string {
        $spectacles = $this->soiree->spectacles;
        $html = "<ul style='list-style-type: none; padding: 0;'>";
        foreach($spectacles as $spectacle) {
            $render = new SpectacleRenderer($spectacle);
            $html .= "<li>" . $render->render(1) . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}

