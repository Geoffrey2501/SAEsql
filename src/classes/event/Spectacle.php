<?php
namespace iutnc\NRV\event;








/**
 * Class Spectacle
 */
class Spectacle
{
    /**
     * @var string
     */
    private string $titre;
    private string $description;
    private ?string $extrait;
    private string $horairePrevisionnel;
    private string $date;

    private string $style;

    /**
     * @var array
     */
    private array $images;
    private array $artistes;



    /**
     * @param string $titre
     * @param string $description
     * @param string|null $extrait
     * @param string $horairePrevisionnel
     * @param array $images
     * @param array $artistes
     */
    public function __construct(string $titre, string $description, ?string $extrait,
                                string $horairePrevisionnel,
                                array  $images, array $artistes, string $date, string $style)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->extrait = $extrait;
        $this->horairePrevisionnel = $horairePrevisionnel;
        $this->images = $images;
        $this->artistes = $artistes;
        $this->date = $date;
        $this->style = $style;
    }


    /**
     * Magic get method
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidArgumentException("Property {$name} does not exist");
    }

    /**
     * Magic set method
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new InvalidArgumentException("Property {$name} does not exist");
        }
    }


}