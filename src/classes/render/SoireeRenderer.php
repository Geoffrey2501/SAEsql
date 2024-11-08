<?php
declare(strict_types=1);

namespace iutnc\NRV\render;

use iutnc\NRV\event\Soiree;

class SoireeRenderer extends EventRenderer {
    protected Soiree $soiree;

    public function __construct(Soiree $soiree) {
        $this->soiree = $soiree;
    }

    public function renderCompact(): string {
        return "<div style='text-align: center; padding: 10px 0;'>
                    <h2>Soirée: " . htmlspecialchars($this->soiree->nomSoiree) . "</h2>
                        <ul style='list-style-type: none; padding: 0;'>      
                            <li><strong>Thème:</strong> " . htmlspecialchars($this->soiree->themeSoiree) . "</li>
                            <li><strong>Date:</strong> " . htmlspecialchars($this->soiree->dateSoiree) . "</li>
                            <li><strong>Lieu:</strong> " . htmlspecialchars($this->soiree->lieuSoiree) . "</li>
                            <li><strong>Heure:</strong> " . htmlspecialchars($this->soiree->heureSoiree) . "</li>
                        </ul>
                </div>";
    }

    public function renderLong() : string
    {
            return "<div style='text-align: center; padding: 10px 0;'>
                    <h2>Soirée: " . htmlspecialchars($this->soiree->nomSoiree) . "</h2>
                        <ul style='list-style-type: none; padding: 0;'>      
                            <li><strong>Thème:</strong> " . htmlspecialchars($this->soiree->themeSoiree) . "</li>
                            <li><strong>Date:</strong> " . htmlspecialchars($this->soiree->dateSoiree) . "</li>
                            <li><strong>Lieu:</strong> " . htmlspecialchars($this->soiree->lieuSoiree) . "</li>
                            <li><strong>Heure:</strong> " . htmlspecialchars($this->soiree->heureSoiree) . "</li>
                            <li><strong>Description:</strong> " . htmlspecialchars($this->soiree->description) . "</li>
                            <li><strong>Spectacles:</strong> " . $this->renderSpectacles() . "</li>
                        </ul>
                </div>";
    }

    public function renderSpectacles(): string {
        $spectacles = $this->soiree->spectacles;
        $html = "<ul style='list-style-type: none; padding: 0;'>";
        foreach($spectacles as $spectacle) {
            $html .= "<li>" . htmlspecialchars($spectacle) . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}

