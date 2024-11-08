<?php

namespace iutnc\NRV\dispatch;



use iutnc\NRV\action\AddSoireeAction;
use iutnc\NRV\action\AddSpectacle;
use iutnc\NRV\action\DefaultAction;
use iutnc\NRV\action\DisplayListeSpectacleAction;
use iutnc\NRV\action\FiltrageAction;
use iutnc\NRV\repository\NRVRepository;
use iutnc\NRV\action\Signin;


class Dispatcher
{
    /**
     * @var string
     */
    private string $action;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->action = $_GET['action']??'default';
    }

    /**
     * @throws \Exception
     * gerer les actions
     */
    public function run(): void
    {
        NRVRepository::setConfig(__DIR__ . '/../../../config/NRV.db.ini');
        switch ($this->action) {

            case 'add-soiree':
                $action = new AddSoireeAction();
                break;

            case 'filtre':
                $action = new FiltrageAction();
                break;

            case 'add-spectacle':
                $action = new AddSpectacle();
                break;
            case 'signin':
                $action = new Signin();
                break;
            case 'add-user':
                $action = new AddUserAction();
                break;
            case 'display-spectacle':
                $action = new DisplayListeSpectacleAction();
                break;

            default:
                $action = new DefaultAction();
                break;
        }
        $html = $action->execute();
        $this->renderPage($html);
    }

    /**
     * @param string $html
     * page html génerer
     * avec dans $html qui est le resultat
     */
    private function renderPage(string $html): void
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='stylesheet' href='style.css'>
            <title>Deefy</title>
            <style> 
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
            align-self: flex-start;
        }
        form input{
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        form button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #1a1a2e;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button[type="submit"]:hover {
            background-color: #4a4a6d;
        }
        </style>
            </nav>
        </head>
        <body>
            <header>
                <h1>Deefy</h1>  
            </header>
            <nav>
                <ul>
                    <li><a href="?action=default">Accueil</a></li>
                    <li><a href="?action=display-spectacle">Afficher spectacle</a></li>
                    <li><a href="?action=signin">Connexion</a></li>
                    <li><a href="?action=add-spectacle">Ajouter Spectacle</a></li>
                    <li><a href="?action=">Vide</a></li>
                    <li><a href="?action=add-soiree">AjouterSoirée</a></li>
                </ul>
            </nav>
            $html
        </body>
        </html>
            
        HTML;
    }
}