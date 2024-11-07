<?php
declare(strict_types=1);

namespace iutnc\NRV\repository;

use iutnc\NRV\event\Lieu;
use iutnc\NRV\event\Spectacle;
use iutnc\NRV\event\Soiree;





/**
 * Class NRVRepository
 */

class NRVRepository
{
    private \PDO $pdo;
    private static ?NRVRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

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


    public function getPDO(): \PDO
    {
        return $this->pdo;
    }
}