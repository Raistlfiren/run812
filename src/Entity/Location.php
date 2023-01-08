<?php

namespace App\Entity;

use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Ramsey\Uuid\Uuid;

/**
 * Class Location
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: LocationRepository::class)]
class Location implements UuidInterface, SluggableInterface
{
    use EntityIdAndUuid;
    use SluggableTrait;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $title;

    /**
     * @var Collection
     */
    #[ManyToMany(targetEntity: Route::class, mappedBy: 'locations')]
    #[OrderBy(['distance' => 'ASC'])]
    private $routes;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->routes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Location
     */
    public function setTitle(string $title): Location
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    /**
     * @param Collection $routes
     * @return Location
     */
    public function setRoutes(Collection $routes): Location
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        if ($this->routes->contains($route)) {
            return;
        }

        $this->routes->add($route);
        $route->addLocation($this);
    }

    /**
     * @param Route $route
     */
    public function removeRoute(Route $route)
    {
        if (!$this->routes->contains($route)) {
            return;
        }

        $this->routes->removeElement($route);
        $route->removeLocation($this);
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['title'];
    }
}