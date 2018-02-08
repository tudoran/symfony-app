<?php

namespace BackOfficeBundle\Repository;

use BackOfficeBundle\Entity\Customer;
use BackOfficeBundle\Entity\CustomerShippingAddress;
use BackOfficeBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class CustomerShippingAddressRepository extends EntityRepository
{
    /**
     * CustomerShippingAddressRepository constructor.
     * @param EntityManager $entityManager
     * @param Mapping\ClassMetadata $metadata
     */
    public function __construct(EntityManager $entityManager, Mapping\ClassMetadata $metadata)
    {
        parent::__construct($entityManager, $metadata);
    }

    public function create()
    {

        return new CustomerShippingAddress($this->_em, $this->_class);
    }
    /**
     * Save instance
     * @param CustomerShippingAddress $customerShippingAddress
     * @param Customer $customer
     * @param \DateTime $dateTime
     * @param User $user
     * @return CustomerShippingAddress
     */
    public function save(CustomerShippingAddress $customerShippingAddress, Customer $customer, \DateTime $dateTime, User $user)
    {

        $customerShippingAddress->setCustomer($customer);
        $customerShippingAddress->setStreetAddress($customer->getStreetAddress());
        $customerShippingAddress->setCity($customer->getCity());
        $customerShippingAddress->setState($customer->getState());
        $customerShippingAddress->setZipCode($customer->getZipCode());
        $customerShippingAddress->setModifiedAt($dateTime);
        $customerShippingAddress->setModifiedBy($user);
        $this->_em->persist($customerShippingAddress);
        $this->_em->flush();

        return $customerShippingAddress;
    }
}
