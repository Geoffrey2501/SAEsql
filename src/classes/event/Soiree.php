<?php
namespace iutnc\NRV\event;

use InvalidArgumentException;

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
    private string $lieuSoiree;
    private string $heureSoiree;

    private array $spectacles;
    private string $description;

    private string $video;

    /**
     * Soiree constructor.
     * @param string $nomSoiree
     * @param string $themeSoiree
     * @param string $dateSoiree
     * @param string $lieuSoiree
     * @param array $spectacles
     */
    public function __construct(string $nomSoiree, string $themeSoiree, string $dateSoiree,
                                string $lieuSoiree, array $spectacles, string $heureSoiree, string $description)
    {
        $this->nomSoiree = $nomSoiree;
        $this->themeSoiree = $themeSoiree;
        $this->dateSoiree = $dateSoiree;
        $this->lieuSoiree = $lieuSoiree;
        $this->spectacles = $spectacles;
        $this->heureSoiree = $heureSoiree;
        $this->description = $description;
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