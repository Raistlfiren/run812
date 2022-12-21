<?php

namespace App\Entity;

use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Uuid;

/**
 * Class Schedule
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: EventRepository::class)]
class Event implements UuidInterface
{
    use EntityIdAndUuid;

    /**
     * @var \DateTime
     */
    #[Column(type: 'datetime')]
    private $datetime;

    /**
     * @var RouteCollection|null
     */
    #[ManyToOne(targetEntity: RouteCollection::class, inversedBy: 'events')]
    private $routeCollection;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     * @return Event
     */
    public function setDatetime(\DateTime $datetime): Event
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return RouteCollection|null
     */
    public function getRouteCollection(): ?RouteCollection
    {
        return $this->routeCollection;
    }

    /**
     * @param RouteCollection|null $routeCollection
     * @return Event
     */
    public function setRouteCollection(?RouteCollection $routeCollection): Event
    {
        $this->routeCollection = $routeCollection;
        return $this;
    }
}