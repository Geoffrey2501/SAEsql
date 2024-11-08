<?php
declare(strict_types=1);


namespace iutnc\NRV\repository;

use iutnc\NRV\event\Soiree;
use iutnc\NRV\event\Spectacle;

class NRVRepository
{
    /**
     * @var \PDO
     */
    private \PDO $pdo;
    /**
     * @var NRVRepository|null
     */
    private static ?NRVRepository $instance = null;
    /**
     * @var array
     */
    private static array $config = [];

    /**
     * NRVRepository constructor.
     * @param array $conf
     */
    private function __construct(array $conf)
    {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * @return NRVRepository
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new NRVRepository(self::$config);
        }
        return self::$instance;
    }

    /**
     * @param string $file
     * @throws \Exception
     */
    public static function getSpectacle()
    {
        $pdo = self::getInstance()->getPDO();
        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = $pdo->prepare("SELECT Spectacle.IdSpec, libelle, titrespec, video,horaire, nomstyle FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle;");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['IdSpec'];
            //requete sql pour recuperer les images des spectacles
            $stmt2 = $pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['chemin'];
            }
            //ajout du spectacle
            $spectacles[$id] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, []);
        }
        return $spectacles;
    }
    /**
     * @param string $file
     * @throws \Exception
     */
    public static function setConfig(string $file)
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        self::$config = [
            'dsn' => $conf['dsn'],
            'user' => $conf['user'],
            'pass' => $conf['pass']
        ];
    }

    /**
     * @param string $libelle
     * @param string $titre
     * @param string $video
     * @param int $style
     */
    public static function addSpectacle(string $libelle, string $titre, string $video, int $style)
    {
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT MAX(idSpec) AS max_id FROM spectacle");
        $stmt->execute();
        $id = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = (int)$id['max_id'] + 1;
        $stmt = $pdo->prepare("INSERT INTO spectacle (idSpec, libelle, titrespec, video, idstyle) VALUES (:id, :libelle, :titre, :video, :style)");
        $stmt->execute([':id' => $id, ':libelle' => $libelle, ':titre' => $titre, ':video' => $video, ':style' => $style]);
    }

    /**
     * @return array
     * retrouner les styles de musique
     */
    public static function getStyles()
    {
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT idStyle, nomstyle FROM Style");
        $stmt->execute();
        $styles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $styles[$row['idStyle']] = $row['nomstyle'];
        }
        return $styles;
    }


    /**
     * @param String $d
     * @return array
     * filtrer les spectacles en fonction de la date
     */
    public static function filtreDate(String $d):array{
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT Spectacle.IdSpec as idspectacle, libelle, titrespec, video,horaire, nomstyle 
                                            FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soireespectacle.idsoiree=soiree.idsoiree
                                            where soiree.date=:d;");
        $stmt->execute([':d'=>$d]);
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['idspectacle'];
            $stmt2 = $pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['chemin'];
            }
            $spectacles[(int)$row['idspectacle']] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, []);
        }
        return $spectacles;
    }
    /**
     * @param Int $id
     * @return array
     * filtrer les spectacles en fonction du lieu
     */
    public static function filtreLieux(Int $id):array{
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT spectacle.idspec as idspectacle, libelle, titrespec, video,horaire, nomstyle 
                                            FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soireespectacle.idsoiree=soiree.idsoiree
                                            where idlieu = :id ");
        $stmt->execute([':id'=>$id]);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['idspectacle']."boucle";
            $stmt2 = $pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['chemin'];
            }
            $spectacles[(int)$row['idspectacle']] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, []);
        }
        return $spectacles;
    }


    /**
     * @param int $styleId
     * @return array
     */
    public static function filtreStyle(int $styleId): array {
        $pdo = self::getInstance()->getPDO();

        // Requête pour récupérer les spectacles filtrés par style
        $stmt = $pdo->prepare("
        SELECT IdSpec, libelle, titrespec, video
        FROM spectacle
        WHERE IdStyle = :styleId");

        $stmt->execute([':styleId' => $styleId]);
        $spectacles = [];

        // Récupération des spectacles
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $idSpec = $row['IdSpec'];

            // Deuxième requête pour récupérer les images du spectacle
            $stmt2 = $pdo->prepare("SELECT chemin FROM spectacleimage SI
                                 INNER JOIN image I ON SI.idimage = I.idimage
                                 WHERE SI.idspec = :idSpec");
            $stmt2->execute([':idSpec' => $idSpec]);

            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['chemin'];
            }

            // Création d'un objet Spectacle avec les données récupérées
            $spectacles[] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], null, $images, []);
        }

        return $spectacles; // Retourner les résultats
    }




    public static function getDates()
    {
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT date FROM soiree");
        $stmt->execute();
        $dates = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $dates[] = $row['date'];
        }
        return $dates;
    }

    public function getLieux()
    {
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT idlieu, nomlieu FROM lieu");
        $stmt->execute();
        $lieux = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $lieux[$row['idlieu']] = $row['nomlieu'];
        }
        return $lieux;
    }

    public function getThemes(){
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT idtheme, nomtheme FROM theme");
        $stmt->execute();
        $themes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $themes[$row['idtheme']] = $row['nomtheme'];
        }
        return $themes;
    }


    /**
     * @return \PDO
     */

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }


    public function addSoiree(Soiree $soiree):bool
    {
       $stmt = $this->pdo->prepare("INSERT INTO soiree (titresoiree, idtheme, date, idlieu, heuresoiree, description) VALUES (:nom, :theme, :date, :lieu, :heure, :description)");
        try {
            $stmt->execute([':nom' => $soiree->nomSoiree, ':theme' => $soiree->themeSoiree, ':date' => $soiree->dateSoiree, ':lieu' => $soiree->lieuSoiree, ':heure' => $soiree->heureSoiree, ':description' => $soiree->description]);
            $res = true;
        }catch (\Exception $e){
            $res = false;
        }
        return $res;
    }




    public static function getSpectacleById(int $idSpectacle) {
        $pdo = self::getInstance()->getPDO();
        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = $pdo->prepare("SELECT Spectacle.IdSpec, libelle, titrespec, video,horaire, nomstyle FROM spectacle
                                            INNER JOIN soireespectacle ON spectacle.idspec=soireespectacle.idspec
                                            INNER JOIN Style ON Spectacle.IdStyle=Style.idStyle
                                            WHERE Spectacle.IdSpec = :id");
        $stmt->execute([':id' => $idSpectacle]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt2 = $pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
        $stmt2->bindParam(':id', $idSpectacle);
        $stmt2->execute();
        $images = [];

        while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            $images[] = $row2['chemin'];
        }
        return new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, []);
    }

    public static function getSoiree(int $idSoiree) {
        $pdo = self::getInstance()->getPDO();

        $stmt = $pdo->prepare("SELECT TitreSoiree, NomTheme, Date, heuresoiree, NomLieu, Descriptif FROM soiree
                                        INNER JOIN theme ON theme.IdTheme=soiree.IdTheme
                                        INNER JOIN lieu ON lieu.IdLieu=soiree.IdLieu
                                        WHERE IdSoiree = :id");
        $stmt->execute(['id' => $idSoiree]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        //requete sql pour recuperer les spectacles de la soiree
        $stmt2 = $pdo->prepare("SELECT IdSpec FROM soireespectacle WHERE IdSoiree = :id");
        $stmt2->execute(['id' => $idSoiree]);
        $spectacles = [];
        while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            $spectacles[] = self::getSpectacleById($row2['IdSpec']);
        }
        return new Soiree($row['TitreSoiree'], $row['NomTheme'], $row['Date'], $row['NomLieu'], $spectacles, $row['heuresoiree'], $row['Descriptif']);

    }

    public function getSoirees():array
    {
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT IdSoiree FROM soiree");
        $stmt->execute();
        $soirees = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $soirees[(int)$row['IdSoiree']] = self::getSoiree((int)$row['IdSoiree']);
        }
        return $soirees;
    }

    public function addSoireeSpectacle(int $idSoiree, int $idSpectacle, string $horaire):bool {
        $pdo = self::getInstance()->getPDO();
        // Check if the spectacle is already scheduled for the same evening
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM soireespectacle 
                           INNER JOIN soiree ON soireespectacle.IdSoiree = soiree.IdSoiree 
                           WHERE soireespectacle.IdSpec = :idSpectacle AND soiree.Date = 
                           (SELECT Date FROM soiree WHERE IdSoiree = :idSoiree)");
        $stmt->execute(['idSoiree' => $idSoiree, 'idSpectacle' => $idSpectacle]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // If the spectacle is not already scheduled, insert the new record
            $stmt = $pdo->prepare("INSERT INTO soireespectacle (IdSoiree, IdSpec, horaire) VALUES (:idSoiree, :idSpectacle, :horaire)");
            $stmt->execute(['idSoiree' => $idSoiree, 'idSpectacle' => $idSpectacle, 'horaire' => $horaire]);
            $res = true;
        } else {
            $res=false;
        }
        return $res;
    }

    public function getAllTitresSpectacles(){
        $pdo = self::getInstance()->getPDO();
        $stmt = $pdo->prepare("SELECT idspec,titrespec FROM spectacle");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $spectacles[$row['idspec']] = $row['titrespec'];
        }
        return $spectacles;
    }
}