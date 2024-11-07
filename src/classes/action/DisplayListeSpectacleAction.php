<?php

namespace iutnc\NRV\action;
use iutnc\NRV\repository\NRVRepository;

/**
 * Class DisplaySpectacle
 */
class DisplayListeSpectacleAction extends Action
{

    /**
     * @return string
     * @throws \Exception
     */
    public function execute(): string
    {
        $soiree = NRVRepository::getInstance()->getSpectacle();
        $html = '<div class="spectacle-list">';
        foreach ($soiree as $spectacle) {
            $html .= '<div class="card" style="width: 18rem;">
                            <img src="' . htmlspecialchars($spectacle->images[0]) . '" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($spectacle->titre) . '</h5>
                                <p class="card-text">Date: ' . null . '</p>
                                <p class="card-text">Horaire: ' . htmlspecialchars($spectacle->horairePrevisionnel) . '</p>
                            </div>
                          </div>';
        }
        $html .= '</div>';
        return $html;
    }
}