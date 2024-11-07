<?php

/**
 * Class DisplaySpectacle
 */
class DisplayListeSpectacleAction extends Action
{

    /**
     * @return string
     */
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $soiree = NRVRepository::getInstance()->getSoiree($id);
            $spectacles = $soiree->spectacles;
            $html = '<div class="spectacle-list">';
            foreach ($spectacles as $spectacle) {
                $html .= '<div class="card" style="width: 18rem;">
                            <img src="' . htmlspecialchars($spectacle->images[0]) . '" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($spectacle->titre) . '</h5>
                                <p class="card-text">Date: ' . htmlspecialchars($soiree->dateSoiree) . '</p>
                                <p class="card-text">Horaire: ' . htmlspecialchars($spectacle->horairePrevisionnel) . '</p>
                            </div>
                          </div>';
            }
            $html .= '</div>';
            return $html;
        }
        return '';
    }
}