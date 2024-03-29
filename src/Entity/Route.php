<?php

namespace App\Entity;

use App\Doctrine\UuidInterface;
use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface as BaseUuidInterface;

/**
 * Class Route
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: RouteRepository::class)]
class Route implements UuidInterface, SluggableInterface
{
    use SluggableTrait;

    /**
     * The unique auto incremented primary key.
     *
     * @var int|null
     */
    #[Id]
    #[Column(type: 'integer', options: ['unsigned' => true])]
    protected $id;

    /**
     * The internal primary identity key.
     *
     * @var \Ramsey\Uuid\UuidInterface
     */
    #[Column(type: 'uuid', unique: true)]
    protected $uuid;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $name;

    /**
     * @var string
     */
    #[Column(type: 'text', nullable: true)]
    private $description;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $distance;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $elevationGain;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $elevationLoss;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $trackType;

    #[Column(type: 'json')]
    private $jsonRoute;

    /**
     * @var Collection
     */
    #[ManyToMany(targetEntity: RouteCollection::class, inversedBy: 'routes', cascade: ['persist'])]
    private $routeCollections;

    /**
     * @var Collection
     */
    #[ManyToMany(targetEntity: Location::class, inversedBy: 'routes')]
    private $locations;

    /**
     * @var Collection
     */
    #[ManyToMany(targetEntity: RunningGroup::class, inversedBy: 'routes')]
    private $runningGroups;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->locations = new ArrayCollection();
        $this->runningGroups = new ArrayCollection();
        $this->routeCollections = new ArrayCollection();
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
     * @return Route
     */
    public function setName(string $name): Route
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getDistance(): float
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return Route
     */
    public function setDistance(float $distance): Route
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsonRoute()
    {
        return $this->jsonRoute;
    }

    /**
     * @param mixed $jsonRoute
     * @return Route
     */
    public function setJsonRoute($jsonRoute)
    {
        $this->jsonRoute = $jsonRoute;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRouteCollections(): Collection
    {
        return $this->routeCollections;
    }

    /**
     * @param Collection $routeCollections
     * @return Route
     */
    public function setRouteCollections(Collection $routeCollections): Route
    {
        $this->routeCollections = $routeCollections;
        return $this;
    }

    public function getFlatLocations()
    {
        $locations = [];

        foreach ($this->locations as $location) {
            $locations[] = $location->getTitle();
        }

        return implode(", ",array_unique($locations));
    }

    public function getFlatRunningGroups()
    {
        $runningGroups = [];

        foreach ($this->runningGroups as $runningGroup) {
            $runningGroups[] = $runningGroup->getTitle();
        }

        return implode(", ",array_unique($runningGroups));
    }

    /**
     * @return Collection
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    /**
     * @param Collection $locations
     * @return Route
     */
    public function setLocations(Collection $locations): Route
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @param Location $location
     */
    public function addLocation(Location $location)
    {
        if ($this->locations->contains($location)) {
            return;
        }

        $this->locations->add($location);
        $location->addRoute($this);
    }

    /**
     * @param Location $location
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);
        $location->removeRoute($this);
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getRunningGroups(): ArrayCollection|Collection
    {
        return $this->runningGroups;
    }

    /**
     * @param ArrayCollection|Collection $runningGroups
     * @return Route
     */
    public function setRunningGroups(ArrayCollection|Collection $runningGroups): Route
    {
        $this->runningGroups = $runningGroups;
        return $this;
    }

    /**
     * @param mixed $runningGroup
     */
    public function addRunningGroup($runningGroup)
    {
        if ($this->runningGroups->contains($runningGroup)) {
            return;
        }

        $this->runningGroups->add($runningGroup);
        $runningGroup->addRoute($this);
    }

    /**
     * @param mixed $runningGroup
     */
    public function removeRunningGroup($runningGroup)
    {
        $this->runningGroups->removeElement($runningGroup);
        $runningGroup->removeRoute(null);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): BaseUuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param BaseUuidInterface $uuid
     *
     * @return self
     */
    public function setUuid(BaseUuidInterface $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function setId($id): Route
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle()
    {
        return $this->name . ' ' . $this->distance;
    }

    /**
     * @param RouteCollection $routeCollection
     */
    public function addRouteCollection(RouteCollection $routeCollection)
    {
        if ($this->routeCollections->contains($routeCollection)) {
            return;
        }

        $this->routeCollections->add($routeCollection);
        $routeCollection->addRoute($this);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    public function removeRouteCollection(RouteCollection $routeCollection)
    {
        if (!$this->routeCollections->contains($routeCollection)) {
            return;
        }

        $this->routeCollections->removeElement($routeCollection);
        $routeCollection->removeRoute($this);
    }

    /**
     * @return float
     */
    public function getElevationGain(): float
    {
        return $this->elevationGain;
    }

    /**
     * @param float $elevationGain
     * @return Route
     */
    public function setElevationGain(float $elevationGain): Route
    {
        $this->elevationGain = $elevationGain;
        return $this;
    }

    /**
     * @return float
     */
    public function getElevationLoss(): float
    {
        return $this->elevationLoss;
    }

    /**
     * @param float $elevationLoss
     * @return Route
     */
    public function setElevationLoss(float $elevationLoss): Route
    {
        $this->elevationLoss = $elevationLoss;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackType(): string
    {
        return $this->trackType;
    }

    /**
     * @param string $trackType
     * @return Route
     */
    public function setTrackType(string $trackType): Route
    {
        $this->trackType = $trackType;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Route
     */
    public function setDescription(string $description): Route
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['name', 'distance'];
    }
}