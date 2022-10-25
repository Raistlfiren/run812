<?php


use App\Doctrine\EntityIdAndUuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Rating
{
    use EntityIdAndUuid;

    /**
     * @var integer
     */
    #[Column(type: 'integer')]
    private $rating;

    /**
     * @var Comment
     */
    #[ManyToOne(targetEntity: Comment::class, inversedBy: 'ratings')]
    private $comment;

    /**
     * @var Question
     */
    #[ManyToOne(targetEntity: Question::class, inversedBy: 'question')]
    private $question;

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return Rating
     */
    public function setRating(int $rating): Rating
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     * @return Rating
     */
    public function setComment(Comment $comment): Rating
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     * @return Rating
     */
    public function setQuestion(Question $question): Rating
    {
        $this->question = $question;
        return $this;
    }
}