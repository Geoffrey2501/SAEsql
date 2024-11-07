<?php
declare(strict_types=1);

use Spectacle;


class SpectacleListRenderer implements Renderer {

    protected array $spectacles;


    public function __construct(array $spectacles) {
        $this->spectacles = $spectacles;
    }


    public function render(int $mode = Renderer::COMPACT): string {
        $html = "<div style='text-align: center; padding: 10px 0;'>
                    <h2>Liste des Spectacles</h2>
                    <ul style='list-style-type: none; padding: 0;'>";

        foreach ($this->spectacles as $spectacle) {
            $html .= $this->renderSpectacle($spectacle);
        }

        $html .= "</ul></div>";

        return $html;
    }


    private function renderSpectacle(Spectacle $spectacle): string {
        $html = "<li style='border-bottom: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>
                    <div style='text-align: left;'>
                        <h3>" . htmlspecialchars($spectacle->titre) . "</h3>
                        <p><strong>Date et horaire:</strong> " . htmlspecialchars($spectacle->horairePrevisionnel) . "</p>
                        <p><strong>Description:</strong> " . htmlspecialchars($spectacle->description) . "</p>
                    </div>";

        // Render les images pour chaque spectacle
        $html .= $this->renderImages($spectacle);

        // Return HTML pour le spectacle item
        $html .= "</li>";

        return $html;
    }

    private function renderImages(Spectacle $spectacle): string {
        $html = "<div class='spectacle-images' style='margin-top: 10px;'>";
        foreach ($spectacle->images as $image) {
            $html .= "<img src='" . htmlspecialchars($image) . "' alt='Spectacle Image' style='width: 100px; height: auto; margin-right: 10px;'>";
        }
        $html .= "</div>";

        return $html;
    }
}




