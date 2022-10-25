<?php


use App\Doctrine\EntityIdAndUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity]
class Question
{
    use EntityIdAndUuid;

    /**
     * @var string
     */
    #[Column(type: 'string')]
    private $question;

    /**
     * @var ArrayCollection|Collection
     */
    #[OneToMany(targetEntity: Rating::class, mappedBy: 'question')]
    private $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return Question
     */
    public function setQuestion(string $question): Question
    {
        $this->question = $question;
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
     * @return Question
     */
    public function setRatings(ArrayCollection|Collection $ratings): Question
    {
        $this->ratings = $ratings;
        return $this;
    }
}