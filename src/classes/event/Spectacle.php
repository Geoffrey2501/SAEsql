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
    private ?string $urlVideo;
    private string $horairePrevisionnel;


    /**
     * @var array
     */
    private array $images;
    private array $artistes;
    private string $date;


    /**
     * @param string $titre
     * @param string $description
     * @param string|null $urlVideo
     * @param string $horairePrevisionnel
     * @param array $images
     * @param array $artistes
     */
    public function __construct(string $titre, string $description, ?string $urlVideo,
                                string $horairePrevisionnel,
                                array $images, array $artistes, string $date)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->urlVideo = $urlVideo;
        $this->horairePrevisionnel = $horairePrevisionnel;
        $this->images = $images;
        $this->artistes = $artistes;
        $this->date = $date;
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