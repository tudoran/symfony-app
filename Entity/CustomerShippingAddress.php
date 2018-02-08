<?php

namespace BackOfficeBundle\Entity;

use BackOfficeBundle\Repository\CustomerShippingAddressRepository;
use JMS\Serializer\SerializerBuilder;

/**
 * CustomerShippingAddress
 */
class CustomerShippingAddress extends CustomerShippingAddressRepository implements \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $streetAddress;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var integer
     */
    private $active = '1';

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\Customer
     */
    private $customer;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;

    public static function getInstance()
    {
        return new Customer(parent::getEntityManager(), parent::getClassMetadata());
    }

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
     * Set streetAddress
     *
     * @param string $streetAddress
     *
     * @return CustomerShippingAddress
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * Get streetAddress
     *
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return CustomerShippingAddress
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return CustomerShippingAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return CustomerShippingAddress
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return CustomerShippingAddress
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return CustomerShippingAddress
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
     * @return CustomerShippingAddress
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
     * Set customer
     *
     * @param \BackOfficeBundle\Entity\Customer $customer
     *
     * @return CustomerShippingAddress
     */
    public function setCustomer(\BackOfficeBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

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
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return CustomerShippingAddress
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
     * @return mixed
     */
    public function serialize()
    {
        $serializer = SerializerBuilder::create()->build();

        return
            $serializer->serialize(get_object_vars($this), 'json');
    }

    /**
     * @param string $serialized
     * @return mixed
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }


    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $deletedBy;


    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return CustomerShippingAddress
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedBy
     *
     * @param \BackOfficeBundle\Entity\User $deletedBy
     *
     * @return CustomerShippingAddress
     */
    public function setDeletedBy(\BackOfficeBundle\Entity\User $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return \BackOfficeBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }


//    public function __construct(EntityManager $entityManager, \Doctrine\ORM\Mapping\ClassMetadata $metadata)
//    {
//
//        $this->_em = $entityManager;
//        $this->_class = $metadata;
//
//        parent::__construct($this->_em , $this->_class);
//    }



//    public static function save(Customer $customer, \DateTime $dateTime, User $user)
//    {
//
//        $address->setCustomer($customer);
//        $address->setStreetAddress($customer->getStreetAddress());
//        $address->setCity($customer->getCity());
//        $address->setState($customer->getState());
//        $address->setZipCode($customer->getZipCode());
//        $address->setModifiedAt($dateTime);
//        $address->setModifiedBy($user);
//        $address->_em->persist($address);
//        $address->_em->flush();
//    }
}
