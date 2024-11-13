<?php
//namespace iutnc\NRV\action;
//
//use iutnc\NRV\action\Action;
//
//class Logout extends Action
//{
//    public function execute(): string
//    {
//
//        switch ($_SERVER['REQUEST_METHOD']) {
//            case 'GET':
//                if (session_status() === PHP_SESSION_NONE) {
//                    session_start();
//                }
//                $_SESSION['user'] = null;
//                return <<<HTML
//        <div class="alert alert-success mt-3 text-center" role="alert">
//            Vous vous êtes déconnecté(e) avec succès !
//        </div>
//HTML;
//            case 'POST':
//                // La deconnexion via GET uniquement
//                return "";
//            default:
//                return "<div>Méthode non autorisée.</div>";
//        }
//    }
////        if (session_status() === PHP_SESSION_NONE) {
////            session_start();
////        }
////
////        // Supprime l'utilisateur connecté et autres données de session si nécessaire
////        $_SESSION['user'] = null;
////
////        return <<<HTML
////        <div class="alert alert-success mt-3 text-center" role="alert">
////            Vous vous êtes déconnecté(e) avec succès !
////        </div>
////HTML;
////    }
//}

namespace iutnc\NRV\action;

use iutnc\NRV\action\Action;

class Logout extends Action
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Verifiaction de session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user'] = null;
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


