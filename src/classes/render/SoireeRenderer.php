<?php
declare(strict_types=1);

namespace iutnc\NRV\render;

use iutnc\NRV\event\Soiree;

class SoireeRenderer implements Renderer {
    protected Soiree $soiree;

    public function __construct(Soiree $soiree) {
        $this->soiree = $soiree;
    }

    public function render(int $mode = Renderer::COMPACT): string {
        // Rendu des détails de la soirée (nom, thème, date, lieu)
        $html = "<div style='text-align: center; padding: 10px 0;'>
                    <h2>Soirée: " . htmlspecialchars($this->soiree->nomSoiree) . "</h2>
                    <p><strong>Thème:</strong> " . htmlspecialchars($this->soiree->themeSoiree) . "</p>
                    <p><strong>Date:</strong> " . htmlspecialchars($this->soiree->dateSoiree) . "</p>
                    <p><strong>Lieu:</strong> " . htmlspecialchars($this->soiree->lieuSoiree) . "</p>
                </div>";

        // Rendu de la liste des spectacles associés à la soirée
        $html .= $this->renderSpectacles();

        return $html;
    }

    /**
     * Render the list of spectacles for the soirée using SpectacleListRenderer.
     * @return string
     */
    private function renderSpectacles(): string {
        // Utiliser SpectacleListRenderer pour rendre les spectacles de la soirée
        $spectacleRenderer = new SpectacleListRenderer($this->soiree->spectacles);
        return $spectacleRenderer->render();
    }
}

