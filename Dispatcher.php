<?php


class Dispatcher
{
    private string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void
    {
        DeefyRepository::setConfig(__DIR__ . '/../../../../config/deefy.db.ini');
        $html = '';
        switch ($this->action) {
            case 'playlist':
                $action = new DisplayPlaylistAction();
                break;
            case 'add-user':
                $action = new AddUserAction();
                break;
            case 'add-playlist':
                $action = new AddPlaylistAction();
                break;
            case 'add-track':
                $action = new AddPodcastTrackAction();
                break;
            case 'signin':
                $action = new Signin();
                break;
                case 'display-playlist':
                $action = new DisplayPlaylist();
                break;
            default:
                $action = new DefaultAction();
                break;
        }
        $html = $action->execute();
        $this->renderPage($html);
    }

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
                    <li><a href="?action=signin">Se connecter</a></li>
                    <li><a href="?action=add-playlist">Créer une playlist</a></li>
                    <li><a href="?action=playlist">Mes playlists</a></li>
                    <li><a href="?action=display-playlist">Afficher la playlist courante</a></li>
                    <li><a href="?action=add-user">Inscrivez-vous</a></li>
                </ul>
            </nav>
            $html
        </body>
        </html>
            
        HTML;
    }
}