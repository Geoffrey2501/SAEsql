<?php


/**
 * Class Lieu
 * Lieux de spectacles
 */
class Lieu
{

    /**
     * @var string
     */
    private string $nom;
    private string $adresse;

    /**
     * @var int
     */
    private int $nombrePlaces;

    /**
     * @var array
     */
    private array $images;


    /**
     * Lieu constructor.
     * @param string $nom
     * @param string $adresse
     * @param int $nombrePlaces
     * @param array $images
     */
    public function __construct(string $nom, string $adresse, int $nombrePlaces, array $images)
    {
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->nombrePlaces = $nombrePlaces;
        $this->images = $images;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("Property $name does not exist");
        }
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * @throws Exception
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new Exception("Property $name does not exist");
        }
    }




}