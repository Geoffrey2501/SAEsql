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
    public  function getSpectacle()
    {
        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = $this->pdo->prepare("SELECT Spectacle.IdSpec, soiree.idsoiree , libelle, titrespec, video, horaire, nomstyle, date FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soiree.idsoiree=soireespectacle.idsoiree ;");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['IdSpec']." ". $row['idsoiree'];
            //requete sql pour recuperer les images des spectacles
            $stmt2 = $this->pdo->prepare("SELECT nom_image FROM spectacleimage  where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['nom_image'];
            }
            $spec = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, [], $row['date'], $row['nomstyle']);
            //ajout du spectacle
            $spectacles[$id] = $spec;
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
     * ajoute un spectacle dans la base de donnée
     * @param String $title
     * @param String $libelle
     * @param String $video
     * @param String $style
     */
    public function addSpectacle(String $title, String $libelle, String $video, String $style)
    {
        $stmt = $this->pdo->prepare("INSERT INTO spectacle ( libelle, titrespec, video, idstyle) VALUES ( :libelle, :titre, :video, :style)");
        $stmt->execute([':libelle' => $libelle, ':titre' => $title, ':video' => $video, ':style' => $style]);
    }


    /**
     * @return array
     * retrouner les styles de musique
     */
    public function getStyles()
    {
        $stmt = $this->pdo->prepare("SELECT idStyle, nomstyle FROM Style");
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
    public function filtreDate(String $d):array{

        $stmt = $this->pdo->prepare("SELECT Spectacle.IdSpec as idspectacle, libelle, titrespec, video,horaire, nomstyle, date
                                            FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soireespectacle.idsoiree=soiree.idsoiree
                                            where soiree.date=:d;");
        $stmt->execute([':d'=>$d]);
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['idspectacle'];
            $stmt2 = $this->pdo->prepare("SELECT nom_image FROM spectacleimage where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['nom_image'];
            }
            $spectacles[(int)$row['idspectacle']] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, [], $row['date'], $row['nomstyle']);
        }
        return $spectacles;
    }
    /**
     * @param Int $id
     * @return array
     * filtrer les spectacles en fonction du lieu
     */
    public function filtreLieux(Int $id):array{

        $stmt = $this->pdo->prepare("SELECT spectacle.idspec as idspectacle, libelle, titrespec, video,horaire, nomstyle, date 
                                            FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soireespectacle.idsoiree=soiree.idsoiree
                                            where idlieu = :id ");
        $stmt->execute([':id'=>$id]);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['idspectacle']."boucle";
            $stmt2 = $this->pdo->prepare("SELECT nom_image FROM spectacleimage where idspec = :id");
            $stmt2->execute(['id' => $id]);
            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['nom_image'];
            }
            $spectacles[(int)$row['idspectacle']] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, [], $row['date'], $row['nomstyle']);
        }
        return $spectacles;
    }


    /**
     * @param int $styleId
     * @return array
     */
    public function filtreStyle(int $styleId): array {


        // Requête pour récupérer les spectacles filtrés par style
        $stmt = $this->pdo->prepare("
        SELECT Spectacle.IdSpec , libelle, titrespec, video,horaire, nomstyle, date
                                            FROM spectacle inner join soireespectacle on spectacle.idspec=soireespectacle.idspec
                                            inner join Style on Spectacle.IdStyle=Style.idStyle
                                            inner join soiree on soireespectacle.idsoiree=soiree.idsoiree
                                            where style.idstyle = :styleId;
      ");

        $stmt->execute([':styleId' => $styleId]);
        $spectacles = [];

        // Récupération des spectacles
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $idSpec = $row['IdSpec'];

            // Deuxième requête pour récupérer les images du spectacle
            $stmt2 = $this->pdo->prepare("SELECT nom_image FROM spectacleimage where idspec = :id");
            $stmt2->execute([':id' => $idSpec]);

            $images = [];
            while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
                $images[] = $row2['nom_image'];
            }

            // Création d'un objet Spectacle avec les données récupérées
            $spectacles[] = new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row['horaire'], $images, [], $row['date'], $row['nomstyle']);
        }

        return $spectacles; // Retourner les résultats
    }


    /**
     * retourne les dates des soirées
     * @return array
     */
    public function getDates()
    {

        $stmt = $this->pdo->prepare("SELECT date FROM soiree");
        $stmt->execute();
        $dates = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $dates[] = $row['date'];
        }
        return $dates;
    }

    /**
     * retourne les lieux des soirées
     * @return array
     */
    public function getLieux()
    {

        $stmt = $this->pdo->prepare("SELECT idlieu, nomlieu FROM lieu");
        $stmt->execute();
        $lieux = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $lieux[$row['idlieu']] = $row['nomlieu'];
        }
        return $lieux;
    }
    /**
     * retourne les themes des soirées
     * @return array
     */
    public function getThemes(){
        $stmt = $this->pdo->prepare("SELECT idtheme, nomtheme FROM theme");
        $stmt->execute();
        $themes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $themes[$row['idtheme']] = $row['nomtheme'];
        }
        return $themes;
    }

    /**
     * retourne les styles des spectacles
     * @param String $style
     * @return int
     */
    public function getStyle(String $style): int{
        $stmt = $this->pdo->prepare("SELECT idstyle FROM style where nomstyle = :style");
        $stmt->execute([':style'=>$style]);
        return $stmt->fetch(\PDO::FETCH_ASSOC)['idstyle'];
    }

    /**
     * retourn l'id du lieu de la soirée
     * @param int $idsoiree
     * @return int
     */
    public function getLieu(int $idsoiree): int{
        $stmt = $this->pdo->prepare("SELECT idlieu FROM soiree where idsoiree = :id");
        $stmt->execute([':id'=>$idsoiree]);
        return $stmt->fetch(\PDO::FETCH_ASSOC)['idlieu'];
    }




    /**
     * @return \PDO
     */

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }

    /**
     * ajoute une soirée dans la base de données
     * si l'ajout est effectué retourne true sinon false
     * @param Soiree $soiree
     * @return bool
     */
    public function addSoiree(Soiree $soiree):bool
    {
       $stmt = $this->pdo->prepare("INSERT INTO soiree (titresoiree, idtheme, date, idlieu, heuresoiree, tarif, Descriptif) VALUES (:nom, :theme, :date, :lieu, :heure, :tarif, :description)");
        try {
            $stmt->execute([':nom' => $soiree->nomSoiree, ':theme' => $soiree->themeSoiree, ':date' => $soiree->dateSoiree, ':lieu' => $soiree->lieuSoiree, ':heure' => $soiree->heureSoiree, ':tarif' => $soiree->tarif, ':description' => $soiree->description]);
            $res = true;
        }catch (\Exception $e){
            echo $e->getMessage();
            $res = false;
        }
        return $res;
    }


    /**
     * retourne le spectacle en fonction de son id
     * @param int $idSpectacle
     * @return Spectacle
     */
    public function getSpectacleById(int $idSpectacle): Spectacle{

        //requete sql pour recuperer les spectacles et leur horaire
        $stmt = $this->pdo->prepare("SELECT Spectacle.IdSpec, libelle, titrespec, video,horaire, nomstyle, date FROM spectacle
                                            INNER JOIN soireespectacle ON spectacle.idspec=soireespectacle.idspec
                                            INNER JOIN Style ON Spectacle.IdStyle=Style.idStyle
                                            INNER JOIN soiree ON soiree.idsoiree=soireespectacle.idsoiree
                                            WHERE Spectacle.IdSpec = :id");
        $stmt->execute([':id' => $idSpectacle]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt2 = $this->pdo->prepare("SELECT nom_image FROM spectacleimage where idspec = :id");
        $stmt2->bindParam(':id', $idSpectacle);
        $stmt2->execute();
        $images = [];

        while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            $images[] = $row2['nom_image'];
        }
        return new Spectacle($row['titrespec'], $row['libelle'], $row['video'], $row["horaire"], $images, $this->getArtiste((int)$idSpectacle), $row['date'], $row['nomstyle']);
    }

    /**
     * retourne la soirée en fonction de son id
     * @param int $idSoiree
     * @return Soiree
     */
    public function getSoiree(int $idSoiree) {

        $stmt = $this->pdo->prepare("SELECT TitreSoiree, NomTheme, Date, heuresoiree, NomLieu, Descriptif, tarif FROM soiree
                                        INNER JOIN theme ON theme.IdTheme=soiree.IdTheme
                                        INNER JOIN lieu ON lieu.IdLieu=soiree.IdLieu
                                        WHERE IdSoiree = :id");
        $stmt->execute(['id' => $idSoiree]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        //requete sql pour recuperer les spectacles de la soiree
        $stmt2 = $this->pdo->prepare("SELECT IdSpec FROM soireespectacle WHERE IdSoiree = :id");
        $stmt2->execute(['id' => $idSoiree]);
        $spectacles = [];
        while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            $spectacles[] = $this->getSpectacleById((int)$row2['IdSpec']);
        }
        return new Soiree($row['TitreSoiree'], $row['NomTheme'], $row['Date'], $row['NomLieu'], $spectacles, $row['heuresoiree'], $row['Descriptif'], $row['tarif']);

    }

    /**
     * retourne toutes les soirées
     * @return array
     */
    public function getSoirees():array
    {
        $stmt = $this->pdo->prepare("SELECT IdSoiree FROM soiree");
        $stmt->execute();
        $soirees = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $soirees[(int)$row['IdSoiree']] = self::getSoiree((int)$row['IdSoiree']);
        }
        return $soirees;
    }

    /**
     * ajoute un spectacle à une soirée
     * @param int $idSoiree
     * @param int $idSpectacle
     * @param string $horaire
     * @return bool
     */
    public function addSoireeSpectacle(int $idSoiree, int $idSpectacle, string $horaire):bool {
        // Check if the spectacle is already scheduled for the same evening
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM soireespectacle 
                           INNER JOIN soiree ON soireespectacle.IdSoiree = soiree.IdSoiree 
                           WHERE soireespectacle.IdSpec = :idSpectacle AND soiree.Date = 
                           (SELECT Date FROM soiree WHERE IdSoiree = :idSoiree)");
        $stmt->execute(['idSoiree' => $idSoiree, 'idSpectacle' => $idSpectacle]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // If the spectacle is not already scheduled, insert the new record
            $stmt = $this->pdo->prepare("INSERT INTO soireespectacle (IdSoiree, IdSpec, horaire) VALUES (:idSoiree, :idSpectacle, :horaire)");
            $stmt->execute(['idSoiree' => $idSoiree, 'idSpectacle' => $idSpectacle, 'horaire' => $horaire]);
            $res = true;
        } else {
            $res=false;
        }
        return $res;
    }

    /**
     * retourne les spectacles
     * @param int $idSoiree
     * @return array
     */
    public function getAllTitresSpectacles(){
        $stmt = $this->pdo->prepare("SELECT idspec,titrespec FROM spectacle");
        $stmt->execute();
        $spectacles = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $spectacles[$row['idspec']] = $row['titrespec'];
        }
        return $spectacles;
    }

    /**
     * rajpute une image à un spectacle
     * @param int $idSpectacle
     * @param string $nomImage
     * @return void
     */
    public  function addImageSpectacle(int $idSpectacle, string $nomImage): void
    {
        $fileName = basename($nomImage);

        $stmt = $this->pdo->prepare("INSERT INTO spectacleimage ( idspec, nom_image) VALUES ( :idspec, :nom)");
        $stmt->execute([':idspec' => $idSpectacle, ':nom' => $fileName]);
    }


    /**
     * retourne l'id du spectacle
     * @param string $libelle
     * @param string $titre
     * @return int
     */
    public function getIdSpectacle(string $libelle, string $titre):int
    {

        $stmt = $this->pdo->prepare("SELECT idSpec FROM spectacle WHERE libelle = :libelle AND titrespec = :titre");
        $stmt->execute([':libelle' => $libelle, ':titre' => $titre]);
        $id = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $id['idSpec'];
    }

    /**
     * retourne l'id de la soirée
     * @param string $date
     * @param int $idspectacle
     * @return int
     */
    public function getIdSoiree(string $date, int $idspectacle):int
    {
        $stmt = $this->pdo->prepare("SELECT soiree.idSoiree FROM soiree inner join soireespectacle WHERE date = :date AND idspec = :idspectacle");
        $stmt->execute([':date' => $date , ':idspectacle' => $idspectacle]);
        $id = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $id['idSoiree'];
    }

    /**
     * retourne les artistes d'un spectacle
     * @param int $idSpec
     * @return array
     */
    public function getArtiste(int $idSpec):array
    {
        $stmt = $this->pdo->prepare("SELECT idartiste, pseudo FROM artiste inner join spectacleartiste on artiste.idartiste=spectacleartiste.idartiste where idspec = :id");
        $stmt->execute([':id' => $idSpec]);
        $artistes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $artistes[$row["idartiste"]] = $row['pseudo'];
            echo $row['pseudo'];
        }

        return $artistes;
    }

    /**
     * retourne les artistes
     * @return array
     */
    public function getArtistes(){
        $stmt = $this->pdo->prepare("SELECT idartiste, pseudo FROM artiste");
        $stmt->execute();
        $artistes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $artistes[$row['idartiste']] = $row['pseudo'];
        }
        return $artistes;
    }


    /**
     * ajoute un artiste à un spectacle
     * @param int $idSpectacle
     * @param int $idArtiste
     * @return void
     */
    public  function addArtisteSpectacle(int $idSpectacle, int $idArtiste): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO spectacleartiste ( idspec, idartiste) VALUES ( :idspec, :idartiste)");
        $stmt->execute([':idspec' => $idSpectacle, ':idartiste' => $idArtiste]);
    }
}