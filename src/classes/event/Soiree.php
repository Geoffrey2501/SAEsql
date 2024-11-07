<?php
namespace iutnc\NRV\event;

/**
 * Class Soiree
 */
class Soiree
{
    /**
     * @var string
     */
    private string $nomSoiree;
    private string $themeSoiree;
    private string $dateSoiree;
    private string $heureSoiree;
    private string $lieuSoiree;
    private string $lieuSpectacle;
    private string $adresseSpectacle;

    /**
     * @var int
     */
    private int $nombrePlaces;

    /**
     * @var array
     */
    private array $spectacles;

    /**
     * Soiree constructor.
     * @param string $nomSoiree
     * @param string $themeSoiree
     * @param string $dateSoiree
     * @param string $heureSoiree
     * @param string $lieuSoiree
     * @param string $lieuSpectacle
     * @param string $adresseSpectacle
     * @param int $nombrePlaces
     * @param array $spectacles
     */
    public function __construct(string $nomSoiree, string $themeSoiree, string $dateSoiree, string $heureSoiree,
                                string $lieuSoiree, string $lieuSpectacle, string $adresseSpectacle, int $nombrePlaces, array $spectacles)
    {
        $this->nomSoiree = $nomSoiree;
        $this->themeSoiree = $themeSoiree;
        $this->dateSoiree = $dateSoiree;
        $this->heureSoiree = $heureSoiree;
        $this->lieuSoiree = $lieuSoiree;
        $this->lieuSpectacle = $lieuSpectacle;
        $this->adresseSpectacle = $adresseSpectacle;
        $this->nombrePlaces = $nombrePlaces;
        $this->spectacles = $spectacles;
    }

    /**
     * magic get
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidArgumentException("Property {$name} does not exist");
    }

    /**
     * magic set
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new InvalidArgumentException("Property {$name} does not exist");
        }
    }






}