<?php

namespace App\Entity;

use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\RouteCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Ramsey\Uuid\Uuid;

/**
 * Class Route
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: RouteCollectionRepository::class)]
class RouteCollection implements UuidInterface, SluggableInterface
{
    use EntityIdAndUuid;
    use SluggableTrait;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $name;


    #[Column(type: 'boolean')]
    private $saturdayRoute;

    /**
     * @var ArrayCollection|Collection
     */
    #[ManyToMany(targetEntity: Route::class, mappedBy: 'routeCollections', cascade: ['persist'])]
    #[JoinTable(name: 'route_collections_routes')]
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return RouteCollection
     */
    public function setName(string $name): RouteCollection
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getRoutes(): ArrayCollection|Collection
    {
        return $this->routes;
    }

    /**
     * @param ArrayCollection|Collection $routes
     * @return RouteCollection
     */
    public function setRoutes(ArrayCollection|Collection $routes): RouteCollection
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @param mixed $route
     */
    public function addRoute($route)
    {
        if ($this->routes->contains($route)) {
            return;
        }

        $this->routes->add($route);
        $route->addRouteCollection($this);
    }

    /**
     * @param mixed $route
     */
    public function removeRoute($route)
    {
        if (!$this->routes->contains($route)) {
            return;
        }

        $this->routes->removeElement($route);
        $route->removeRouteCollection($this);
    }

    /**
     * @return mixed
     */
    public function isSaturdayRoute()
    {
        return $this->saturdayRoute;
    }

    /**
     * @param mixed $saturdayRoute
     * @return RouteCollection
     */
    public function setSaturdayRoute($saturdayRoute)
    {
        $this->saturdayRoute = $saturdayRoute;
        return $this;
    }

    public function getLocations()
    {
        $locations = [];

        foreach ($this->routes as $route) {
            foreach ($route->getLocations() as $location) {
                $locations[] = $location->getTitle();
            }
        }

        return array_unique($locations);
    }

    public function getDistances()
    {
        $distances = [];

        foreach ($this->routes as $route) {
            $distances[] = $route->getDistance();
        }

        return $distances;
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['name'];
    }
}