<?php
namespace iutnc\NRV\action;

use iutnc\NRV\action\Action;
use iutnc\NRV\auth\AuthnProvider;
use iutnc\NRV\exception\AuthnException;
use \iutnc\NRV\repository\NRVRepository;
class Signin extends Action
{

    /**
     * revoie un formulaire de connexion si la méthode est GET
     * sinon vérifie les identifiants et connecte l'utilisateur
     * @return string
     */
    public function execute(): string
    {
        $repo = NRVRepository::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            //echo "email : ".$email;
            //echo "password : ".$password;
            try {
                AuthnProvider::signin($repo->getPDO(), $email, $password);
                $html="<h1>Vous êtes connecté</h1>";
            } catch (AuthnException $e) {

                $html="<h1>Identification incorrect</h1>
                <p>".$e->getMessage()."</p>";
            }
        }
        else{
                $html ="
                            <form method='post'>
                                <label for='email'>Email:</label>
                                <input type='email' id='email' name='email' required><br>
                                <label for='password'>Mot de passe:</label>
                                <input type='password' id='password' name='password' required><br>
                                <button type='submit'>Se connecter</button>
                            </form>";
            }

        return $html;
        }

}