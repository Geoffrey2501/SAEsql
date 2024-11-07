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
     */
    public function __construct(string $nomSoiree, string $themeSoiree, string $dateSoiree, string $heureSoiree, string $lieuSoiree, array $spectacles)
    {
        $this->nomSoiree = $nomSoiree;
        $this->themeSoiree = $themeSoiree;
        $this->dateSoiree = $dateSoiree;
        $this->heureSoiree = $heureSoiree;
        $this->lieuSoiree = $lieuSoiree;
        $this->spectacles = $spectacles;
    }

    /**
     * magic get
     * @return string
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