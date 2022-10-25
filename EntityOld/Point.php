<?php


use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity()]
class Point implements UuidInterface
{
    use EntityIdAndUuid;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $latitude;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $longitude;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $elevation;

    /**
     * @var Route
     */
    #[ManyToOne(targetEntity: Route::class, inversedBy: 'route')]
    private $route;

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Point
     */
    public function setLatitude(float $latitude): Point
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Point
     */
    public function setLongitude(float $longitude): Point
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getElevation(): float
    {
        return $this->elevation;
    }

    /**
     * @param float $elevation
     * @return Point
     */
    public function setElevation(float $elevation): Point
    {
        $this->elevation = $elevation;
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
     * @return Point
     */
    public function setRoute(Route $route): Point
    {
        $this->route = $route;
        return $this;
    }
}