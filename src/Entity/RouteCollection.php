<?php

namespace App\Entity;

use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Ramsey\Uuid\Uuid;

/**
 * Class Route
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: RouteRepository::class)]
class RouteCollection implements UuidInterface, SluggableInterface
{
    use EntityIdAndUuid;
    use SluggableTrait;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $name;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Route::class, mappedBy: 'routeCollection', cascade: ['persist'])]
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
        $this->routes->add($route);
        // uncomment if you want to update other side
        $route->setRouteCollection($this);
    }

    /**
     * @param mixed $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
        // uncomment if you want to update other side
        $route->setRouteCollection(null);
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['name'];
    }
}