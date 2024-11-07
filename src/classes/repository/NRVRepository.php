<?php
declare(strict_types=1);


namespace iutnc\NRV\repository;

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
    public function getSpectacle()
    {
        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = $this->pdo->prepare("SELECT Spectacle.IdSpec, libelle, titrespec, video,horaire, nomstyle FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle;");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['IdSpec'];
            //requete sql pour recuperer les images des spectacles
            $stmt2 = $this->pdo->prepare("SELECT chemin FROM spectacleimage inner join Image on spectacleimage.idimage=Image.idimage where idspec = :id");
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
     * @return \PDO
     */

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }
}