<?php

namespace BackOfficeBundle\Entity;

/**
 * MediaShipment
 */
class MediaShipment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $trackingNumber;

    /**
     * @var string
     */
    private $trackingStamp;

    /**
     * @var \DateTime
     */
    private $shippedAt;

    /**
     * @var binary
     */
    private $token;

    /**
     * @var integer
     */
    private $active = '1';

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\Carrier
     */
    private $carrier;

    /**
     * @var \BackOfficeBundle\Entity\CustomerShippingAddress
     */
    private $customerShippingAddress;

    /**
     * @var \BackOfficeBundle\Entity\Product
     */
    private $product;

    /**
     * @var \BackOfficeBundle\Entity\ShipmentStatus
     */
    private $shipmentStatus;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set trackingNumber
     *
     * @param string $trackingNumber
     *
     * @return PendingShipment
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    /**
     * Get trackingNumber
     *
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * Set trackingStamp
     *
     * @param string $trackingStamp
     *
     * @return PendingShipment
     */
    public function setTrackingStamp($trackingStamp)
    {
        $this->trackingStamp = $trackingStamp;

        return $this;
    }

    /**
     * Get trackingStamp
     *
     * @return string
     */
    public function getTrackingStamp()
    {
        return $this->trackingStamp;
    }

    /**
     * Set shippedAt
     *
     * @param \DateTime $shippedAt
     *
     * @return PendingShipment
     */
    public function setShippedAt($shippedAt)
    {
        $this->shippedAt = $shippedAt;

        return $this;
    }

    /**
     * Get shippedAt
     *
     * @return \DateTime
     */
    public function getShippedAt()
    {
        return $this->shippedAt;
    }

    /**
     * Set token
     *
     * @param binary $token
     *
     * @return PendingShipment
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return binary
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return PendingShipment
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return PendingShipment
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set carrier
     *
     * @param \BackOfficeBundle\Entity\Carrier $carrier
     *
     * @return PendingShipment
     */
    public function setCarrier(\BackOfficeBundle\Entity\Carrier $carrier = null)
    {
        $this->carrier = $carrier;

        return $this;
    }

    /**
     * Get carrier
     *
     * @return \BackOfficeBundle\Entity\Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * Set customer
     *
     * @param \BackOfficeBundle\Entity\CustomerShippingAddress $customer
     *
     * @return PendingShipment
     */
    public function setCustomer(\BackOfficeBundle\Entity\CustomerShippingAddress $customerShippingAddress = null)
    {
        $this->customerShippingAddress = $customerShippingAddress;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \BackOfficeBundle\Entity\CustomerShippingAddress
     */
    public function getCustomerShippingAddress()
    {
        return $this->customerShippingAddress;
    }

    /**
     * Set product
     *
     * @param \BackOfficeBundle\Entity\Product $product
     *
     * @return PendingShipment
     */
    public function setProduct(\BackOfficeBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \BackOfficeBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set shipmentStatus
     *
     * @param \BackOfficeBundle\Entity\ShipmentStatus $shipmentStatus
     *
     * @return PendingShipment
     */
    public function setShipmentStatus(\BackOfficeBundle\Entity\ShipmentStatus $shipmentStatus = null)
    {
        $this->shipmentStatus = $shipmentStatus;

        return $this;
    }

    /**
     * Get shipmentStatus
     *
     * @return \BackOfficeBundle\Entity\ShipmentStatus
     */
    public function getShipmentStatus()
    {
        return $this->shipmentStatus;
    }

    /**
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return PendingShipment
     */
    public function setModifiedBy(\BackOfficeBundle\Entity\User $modifiedBy = null)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get modifiedBy
     *
     * @return \BackOfficeBundle\Entity\User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set customerShippingAddress
     *
     * @param \BackOfficeBundle\Entity\CustomerShippingAddress $customerShippingAddress
     *
     * @return MediaShipment
     */
    public function setCustomerShippingAddress(\BackOfficeBundle\Entity\CustomerShippingAddress $customerShippingAddress = null)
    {
        $this->customerShippingAddress = $customerShippingAddress;

        return $this;
    }
    /**
     * @var \BackOfficeBundle\Entity\Customer
     */
    private $customer;


    /**
     * Get customer
     *
     * @return \BackOfficeBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @var integer
     */
    private $stampStatusId = '1';


    /**
     * Set stampStatusId
     *
     * @param integer $stampStatusId
     *
     * @return Shipment
     */
    public function setStampStatusId($stampStatusId)
    {
        $this->stampStatusId = $stampStatusId;

        return $this;
    }

    /**
     * Get stampStatusId
     *
     * @return integer
     */
    public function getStampStatusId()
    {
        return $this->stampStatusId;
    }

    /**
     * @var integer
     */
    private $tokenId = '0';


    /**
     * Set tokenId
     *
     * @param integer $tokenId
     *
     * @return Shipment
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;

        return $this;
    }

    /**
     * Get tokenId
     *
     * @return integer
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }
}
