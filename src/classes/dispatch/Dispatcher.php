<?php

namespace iutnc\NRV\dispatch;


use iutnc\NRV\action\AddUserAction;
use iutnc\NRV\action\AddSoireeAction;
use iutnc\NRV\action\AddSpectacle;
use iutnc\NRV\action\AddSpectacle2Soiree;
use iutnc\NRV\action\DefaultAction;
use iutnc\NRV\action\DisplayListeSpectacleAction;
use iutnc\NRV\action\DisplaySoiree;
use iutnc\NRV\action\DisplaySpectacle;
use iutnc\NRV\action\FiltrageAction;
use iutnc\NRV\action\UserLogOutAction;
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
     * @throws \Exception
     */
    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
        NRVRepository::setConfig(__DIR__ . '/../../../../config/NRV.db.init');
    }

    /**
     * @throws \Exception
     * gerer les actions
     */
    public function run(): void
    {
        switch ($this->action) {
            case 'display-spectacle-filtre':
                $action = new DisplaySpectacle();
                break;
            case 'soiree':
                $action = new DisplaySoiree();
                break;
            case 'add-spectacle2soiree':
                $action = new AddSpectacle2Soiree();
                break;

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
            case 'user-logout':
                $action = new UserLogOutAction();
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
        $menu = "
          <li><a href='?action=default'>Accueil</a></li>
            <li><a href='?action=display-spectacle'>Afficher spectacle</a></li>
            <li><a href='?action=signin'>Connexion</a></li>
            <li><a href='?action=filtre'>Recherche</a></li>
        ";

        if (isset($_SESSION['user'])) {
            $menu .= "<li><a href='?action=add-spectacle'>Ajouter spectacle</a></li>";
            $menu .= "<li><a href='?action=add-soiree'>Ajouter soirée</a></li>";
            $menu .= "<li><a href='?action=add-spectacle2soiree'>Ajouter spectacle à une soirée</a></li>";
            $menu .= "<li><a href='?action=user-logout'>Se déconnecter</a></li>";
            if($_SESSION['user']['role'] == 100){
                $menu .= "<li><a href='?action=add-user'>Ajouter utilisateur</a></li>";
            }
        }

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NRV Festival</title>
        <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #66ffe0, #3399cc);
            color: #f5f5f5;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Style de l'en-tête */
        header {
            background: linear-gradient(135deg, #66b3ff, #3399ff);
            padding: 40px 20px;
            text-align: center;
        }

        header h1 {
            font-size: 3em;
            color: #fff;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 3px;
        }

        header p {
            font-size: 1.2em;
            color: #ffd180;
            margin-top: 10px;
        }

         nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
            background-color: #333;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1em;
        }

        nav ul li a:hover {
            color: #ff3d00;
        }

        /* Effet de survol pour une meilleure interactivité */
        nav ul li a:active {
            transform: scale(0.95);
        }
        .contenu{
           display: flex;
           flex-direction: row;  
           flex-wrap: wrap;
           justify-content: center; /* Centre les éléments horizontalement */
            align-items: center;
        }
        form {
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            margin: 20px;
            background: linear-gradient(135deg, #ccffdd, #a3e6b4);
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: dodgerblue;
        }

        form input[type='text'],
        form input[type='file'],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #555;
            color: black;
        }

        form input[type='text']::placeholder {
            
        }

        form input[type='file'] {
            padding: 5px;
        }

        form select {
            cursor: pointer;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: dodgerblue;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 40px 0 0 0;
        }

        form button:hover {
            background-color: #ff3d00;
        }
        </style>
        </head>
        <body>
        <header>
        <h1>NRV Festival</h1>
        <p>Le plus grand festival de musique de l'année !</p>
        </header>
        
        
        <nav>
        <ul>
        $menu
        </ul>
        
        </nav>
        <div class="contenu">
         $html
        </div>
        </body>
        </html>

        HTML;
    }
}