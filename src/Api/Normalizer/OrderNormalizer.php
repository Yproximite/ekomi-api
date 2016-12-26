<?php

namespace Yproximite\Ekomi\Api\Normalizer;

use Yproximite\Ekomi\Api\Model\Order;
use Yproximite\Ekomi\Api\Model\Feedback;
use Yproximite\Ekomi\Api\Model\OrderCustomData;

/**
 * Class OrderNormalizer
 */
class OrderNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($data)
    {
        $orderCustomData = new OrderCustomData();

        if (array_key_exists('client_id', $data['custom_data'])) {
            $orderCustomData->setClientId($data['custom_data']['client_id']);
        }

        if (array_key_exists('transaction_date', $data['custom_data'])) {
            $orderCustomData->setTransactionDate(new \DateTime($data['custom_data']['transaction_date']));
        }

        if (array_key_exists('project_type', $data['custom_data'])) {
            $orderCustomData->setProjectType($data['custom_data']['project_type']);
        }

        if (array_key_exists('project_location', $data['custom_data'])) {
            $orderCustomData->setProjectLocation($data['custom_data']['project_location']);
        }

        if (array_key_exists('vendor_id', $data['custom_data'])) {
            $orderCustomData->setVendorId($data['custom_data']['vendor_id']);
        }

        $feedback = new Feedback();
        $feedback
            ->setEndCustomerSubmitDate(new \DateTime($data['feedback']['end_customer_submit_date']))
            ->setRating($data['rating'])
            ->setReview($data['review'])
            ->setComment($data['comment'])
        ;

        $order = new Order();
        $order
            ->setCreated(new \DateTime($data['created']))
            ->setUpdated(new \DateTime($data['updated']))
            ->setOrderId($data['order_id'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email'])
            ->setCustomData($orderCustomData)
            ->setRegisterDate(new \DateTime($data['register_date']))
            ->setEndCustomerContactDate(new \DateTime($data['end_customer_contact_date']))
            ->setProductIds($data['product_ids'])
            ->setReviewLink($data['review_link'])
            ->setFeedback($feedback)
        ;

        return $order;
    }
}
