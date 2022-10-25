<?php


use App\Doctrine\EntityIdAndUuid;
use App\Doctrine\UuidInterface;
use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;

/**
 * Class Route
 * @package App\Entity
 *
 */
#[Entity(repositoryClass: RouteRepository::class)]
class Route implements UuidInterface, SluggableInterface
{
    use EntityIdAndUuid;
    use SluggableTrait;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $name;

    /**
     * @var float
     */
    #[Column(type: 'float')]
    private $distance;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Point::class, mappedBy: 'route', cascade: ['persist'])]
    private $points;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Direction::class, mappedBy: 'route')]
    private $directions;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Comment::class, mappedBy: 'route')]
    private $comments;

    public function __construct()
    {
        $this->points = new ArrayCollection();
        $this->directions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * @return ArrayCollection|Collection
     */
    public function getPoints(): ArrayCollection|Collection
    {
        return $this->points;
    }

    /**
     * @param ArrayCollection|Collection $points
     * @return Route
     */
    public function setPoints(ArrayCollection|Collection $points): Route
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getDirections(): ArrayCollection|Collection
    {
        return $this->directions;
    }

    /**
     * @param ArrayCollection|Collection $directions
     * @return Route
     */
    public function setDirections(ArrayCollection|Collection $directions): Route
    {
        $this->directions = $directions;
        return $this;
    }

    /**
     * @param mixed $point
     */
    public function addPoint($point)
    {
        $this->points->add($point);
        // uncomment if you want to update other side
        $point->setRoute($this);
    }

    /**
     * @param mixed $point
     */
    public function removePoint($point)
    {
        $this->points->removeElement($point);
        // uncomment if you want to update other side
        $point->setRoute(null);
    }

    /**
     * @param mixed $direction
     */
    public function addDirection($direction)
    {
        $this->directions->add($direction);
        // uncomment if you want to update other side
        $direction->setRoute($this);
    }

    /**
     * @param mixed $direction
     */
    public function removeDirection($direction)
    {
        $this->directions->removeElement($direction);
        // uncomment if you want to update other side
        $direction->setRoute(null);
    }

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['title'];
    }

    public function generateSlugValue($values): string
    {
        return implode('-', $values);
    }
}