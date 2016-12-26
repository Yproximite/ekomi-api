<?php

namespace Yproximite\Ekomi\Api\Model;

/**
 * Class OrderCustomData
 */
class OrderCustomData
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
     * @return string|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     *
     * @return OrderCustomData
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param \DateTime|null $transactionDate
     *
     * @return OrderCustomData
     */
    public function setTransactionDate(\DateTime $transactionDate = null)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @param string|null $projectType
     *
     * @return OrderCustomData
     */
    public function setProjectType($projectType)
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProjectLocation()
    {
        return $this->projectLocation;
    }

    /**
     * @param string|null $projectLocation
     *
     * @return OrderCustomData
     */
    public function setProjectLocation($projectLocation)
    {
        $this->projectLocation = $projectLocation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * @param string|null $vendorId
     *
     * @return OrderCustomData
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }
}
