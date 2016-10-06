<?php

namespace Yproximite\Ekomi\Api\Model;

/**
 * Class Feedback
 */
class Feedback
{
    /**
     * @var \DateTime
     */
    private $endCustomerSubmitDate;

    /**
     * @var int
     */
    private $rating;

    /**
     * @var string
     */
    private $review;

    /**
     * @var string
     */
    private $comment;

    /**
     * @return \DateTime
     */
    public function getEndCustomerSubmitDate()
    {
        return $this->endCustomerSubmitDate;
    }

    /**
     * @param \DateTime $endCustomerSubmitDate
     *
     * @return Feedback
     */
    public function setEndCustomerSubmitDate(\DateTime $endCustomerSubmitDate)
    {
        $this->endCustomerSubmitDate = $endCustomerSubmitDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     *
     * @return Feedback
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param string $review
     *
     * @return Feedback
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return Feedback
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
}
