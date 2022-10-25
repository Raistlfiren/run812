<?php


use App\Doctrine\EntityIdAndUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[Entity]
class Comment implements TimestampableInterface
{
    use EntityIdAndUuid;
    use TimestampableTrait;

    /**
     * @var string|null
     */
    #[Column(type: 'string', nullable: true)]
    private $comment;

    /**
     * @var Route
     */
    #[ManyToOne(targetEntity: Route::class, inversedBy: 'comments')]
    private $route;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Rating::class, mappedBy: 'comment')]
    private $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return Comment
     */
    public function setComment(?string $comment): Comment
    {
        $this->comment = $comment;
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
     * @return Comment
     */
    public function setRoute(Route $route): Comment
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getRatings(): ArrayCollection|Collection
    {
        return $this->ratings;
    }

    /**
     * @param ArrayCollection|Collection $ratings
     * @return Comment
     */
    public function setRatings(ArrayCollection|Collection $ratings): Comment
    {
        $this->ratings = $ratings;
        return $this;
    }

    /**
     * @param mixed $rating
     */
    public function addRating($rating)
    {
        $this->ratings->add($rating);
        // uncomment if you want to update other side
        $rating->setComment($this);
    }

    /**
     * @param mixed $rating
     */
    public function removeRating($rating)
    {
        $this->ratings->removeElement($rating);
        // uncomment if you want to update other side
        $rating->setComment(null);
    }
}