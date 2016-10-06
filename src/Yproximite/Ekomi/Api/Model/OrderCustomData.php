<?php

namespace Yproximite\Ekomi\Api\Model;

/**
 * Class OrderCustomData
 */
class OrderCustomData
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var \DateTime
     */
    private $transactionDate;

    /**
     * @var string
     */
    private $projectType;

    /**
     * @var string
     */
    private $projectLocation;

    /**
     * @var string
     */
    private $vendorId;

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return OrderCustomData
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param \DateTime $transactionDate
     *
     * @return OrderCustomData
     */
    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @param string $projectType
     *
     * @return OrderCustomData
     */
    public function setProjectType($projectType)
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectLocation()
    {
        return $this->projectLocation;
    }

    /**
     * @param string $projectLocation
     *
     * @return OrderCustomData
     */
    public function setProjectLocation($projectLocation)
    {
        $this->projectLocation = $projectLocation;

        return $this;
    }

    /**
     * @return string
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * @param string $vendorId
     *
     * @return OrderCustomData
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }
}
