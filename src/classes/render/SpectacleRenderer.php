<?php
declare(strict_types=1);

namespace iutnc\NRV\render;
use iutnc\NRV\event\Spectacle;



class SpectacleRenderer extends EventRenderer {

    protected Spectacle $spectacle;


    public function __construct(Spectacle $spec) {
        $this->spectacle = $spec;
    }

    public function renderCompact(): string {

        return "<div style='text-align: center; padding: 10px 0;'>
                    <h2>" . htmlspecialchars($this->spectacle->titre) . "</h2>
                        <ul style='list-style-type: none; padding: 0;'>
                        <li><strong>Date:</strong> " . htmlspecialchars("") . "</li>
                        <li><strong>Horaire:</strong> " . htmlspecialchars($this->spectacle->horairePrevisionnel) . "</li>
                        <li><strong>Description:</strong> " . htmlspecialchars($this->spectacle->description) . "</li>
                    </ul>
                </div>";
    }

    public function renderLong(): string {
        return "<div style='text-align: center; padding: 10px 0;'>
                    <h2>" . htmlspecialchars($this->spectacle->titre) . "</h2>
                    <ul style='list-style-type: none; padding: 0;'>
                        <li><strong>Artiste:</strong> " . htmlspecialchars($this->renderArtistes()) . "</li>
                        <li><strong>Date:</strong> " . htmlspecialchars("") . "</li>
                        <li><strong>Horaire:</strong> " . htmlspecialchars($this->spectacle->horairePrevisionnel) . "</li>
                        <li><strong>Description:</strong> " . htmlspecialchars($this->spectacle->description) . "</li>
                    </ul>
                </div>";
    }

    public function renderArtistes(): string {

        $artistes = $this->spectacle->artistes;
        if (empty($artistes)) {
            return '';
        }

        $html = "<ul style='list-style-type: none; padding: 0;'>";
        foreach($artistes as $artiste) {
            $html .= "<li>" . htmlspecialchars($artiste) . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }

}




