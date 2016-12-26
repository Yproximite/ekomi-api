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
        $clientId = array_key_exists('client_id', $data['custom_data'])
            ? $data['custom_data']['client_id']
            : null
        ;
        $transactionDate = array_key_exists('transaction_date', $data['custom_data'])
            ? new \DateTime($data['custom_data']['transaction_date'])
            : null
        ;
        $projectType = array_key_exists('project_type', $data['custom_data'])
            ? $data['custom_data']['project_type']
            : null
        ;
        $projectLocation = array_key_exists('project_location', $data['custom_data'])
            ? $data['custom_data']['project_location']
            : null
        ;
        $vendorId = array_key_exists('vendor_id', $data['custom_data'])
            ? $data['custom_data']['vendor_id']
            : null
        ;

        $orderCustomData = new OrderCustomData();
        $orderCustomData
            ->setClientId($clientId)
            ->setTransactionDate($transactionDate)
            ->setProjectType($projectType)
            ->setProjectLocation($projectLocation)
            ->setVendorId($vendorId)
        ;

        $comment = array_key_exists('comment', $data['feedback']) ? $data['feedback']['comment'] : null;

        $feedback = new Feedback();
        $feedback
            ->setEndCustomerSubmitDate(new \DateTime($data['feedback']['end_customer_submit_date']))
            ->setRating($data['feedback']['rating'])
            ->setReview($data['feedback']['review'])
            ->setComment($comment)
        ;

        $endCustomerContactDate = array_key_exists('end_customer_contact_date', $data)
            ? new \DateTime($data['end_customer_contact_date'])
            : null
        ;

        $reviewLink = array_key_exists('review_link', $data) ? $data['review_link'] : null;

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
            ->setEndCustomerContactDate($endCustomerContactDate)
            ->setProductIds($data['product_ids'])
            ->setReviewLink($reviewLink)
            ->setFeedback($feedback)
        ;

        return $order;
    }
}
