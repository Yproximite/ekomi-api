<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Model\Order;

use Yproximite\Ekomi\Api\Model\ModelInterface;

/**
 * Class Feedback
 */
class Feedback implements ModelInterface
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
     * Feedback constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->endCustomerSubmitDate = new \DateTime($data['end_customer_submit_date']);
        $this->rating                = (int) $data['rating'];
        $this->review                = str_replace("\\n", "\n", $data['review']);
        $this->comment               = array_key_exists('comment', $data) ? (string) $data['comment'] : null;
    }

    /**
     * @return \DateTime
     */
    public function getEndCustomerSubmitDate(): \DateTime
    {
        return $this->endCustomerSubmitDate;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getReview(): string
    {
        return $this->review;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
}
