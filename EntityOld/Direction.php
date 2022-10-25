<?php


use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity()]
class Direction implements UuidInterface
{
    use EntityIdAndUuid;

    /**
     * @var float|null
     */
    #[Column(type: 'float', nullable: true)]
    private $latitude;

    /**
     * @var float|null
     */
    #[Column(type: 'float', nullable: true)]
    private $longitude;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $cue;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $direction;

    /**
     * @var Route
     */
    #[ManyToOne(targetEntity: Route::class, inversedBy: 'directions')]
    private $route;

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     * @return Direction
     */
    public function setLatitude(?float $latitude): Direction
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     * @return Direction
     */
    public function setLongitude(?float $longitude): Direction
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getCue(): string
    {
        return $this->cue;
    }

    /**
     * @param string $cue
     * @return Direction
     */
    public function setCue(string $cue): Direction
    {
        $this->cue = $cue;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     * @return Direction
     */
    public function setDirection(string $direction): Direction
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     * @return Direction
     */
    public function setRoute(Route $route): Direction
    {
        $this->route = $route;
        return $this;
    }
}