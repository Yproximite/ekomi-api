<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Model\Order;

use Yproximite\Ekomi\Api\Model\ModelInterface;

/**
 * Class OrderCustomData
 */
class OrderCustomData implements ModelInterface
{
    /**
     * @var string|null
     */
    private $clientId;

    /**
     * @var \DateTime|null
     */
    private $transactionDate;

    /**
     * @var string|null
     */
    private $projectType;

    /**
     * @var string|null
     */
    private $projectLocation;

    /**
     * @var string|null
     */
    private $vendorId;

    /**
     * OrderCustomData constructor.
     */
    public function __construct(array $data)
    {
        $this->clientId    = array_key_exists('client_id', $data) ? (string) $data['client_id'] : null;
        $this->projectType = array_key_exists('project_type', $data) ? (string) $data['project_type'] : null;
        $this->vendorId    = array_key_exists('vendor_id', $data) ? (string) $data['vendor_id'] : null;

        $this->transactionDate = array_key_exists('transaction_date', $data)
            ? new \DateTime($data['transaction_date'])
            : null
        ;

        $this->projectLocation = array_key_exists('project_location', $data)
            ? (string) $data['project_location']
            : null
        ;
    }

    /**
     * @return string|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return \DateTime|null
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @return string|null
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @return string|null
     */
    public function getProjectLocation()
    {
        return $this->projectLocation;
    }

    /**
     * @return string|null
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
}
