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
        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = pdo->prepare("SELECT Spectacle.IdSpec, libelle, titrespec, video,horaire, nomstyle FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle;");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['IdSpec'];
            //requete sql pour recuperer les images des spectacles
            $stmt2 = pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
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
     * @return \PDO
     */

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }
}