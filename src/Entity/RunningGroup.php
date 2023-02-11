<?php

namespace App\Entity;

use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\RunningGroupRepository;
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
 * Class RunningGroup
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: RunningGroupRepository::class)]
class RunningGroup implements UuidInterface, SluggableInterface
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
    #[ManyToMany(targetEntity: Route::class, mappedBy: 'runningGroups')]
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
    public function setTitle(string $title): RunningGroup
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
    public function setRoutes(Collection $routes): RunningGroup
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
        $route->addRunningGroup($this);
    }

    /**
     * @param Route $route
     */
    public function removeRoute(Route $route)
    {
        $this->routes->removeElement($route);
        $route->removeRunningGroup($this);
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['title'];
    }
}