<?php
declare(strict_types=1);


namespace iutnc\NRV\repository;


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
            $spectacles[] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, []);
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
    public static function getStyles(): array
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
     * @return \PDO
     */

    public function getPDO(): \PDO
    {
        return $this->pdo;
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

    public static function getLieux()
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
}