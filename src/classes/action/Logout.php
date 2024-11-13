<?php

namespace iutnc\NRV\action;

use iutnc\NRV\action\Action;

class Logout extends Action
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            session_unset();
            session_destroy();

            // Deconnexion
            return <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Vous vous êtes déconnecté(e) avec succès !
        </div>
HTML;
        }

        else {
            // La deconnexion via GET uniquement
            return "";
        }
    }
}


