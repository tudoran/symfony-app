<?php

namespace BackOfficeBundle\Repository;

use BackOfficeBundle\Entity\Carrier;
use BackOfficeBundle\Entity\Customer;
use BackOfficeBundle\Entity\CustomerShippingAddress;
use BackOfficeBundle\Entity\Product;
use BackOfficeBundle\Entity\Shipment;
use BackOfficeBundle\Entity\ShipmentStatus;
use BackOfficeBundle\Entity\Token;
use BackOfficeBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ShipmentRepository extends EntityRepository
{
    /**
     * @var
     */
    protected $orders;

    /**
     * @var
     */
    protected $shipment;

    /**
     * @var
     */
    protected $periodDate;

    /**
     * @var
     */
    protected $periodDays;

    /**
     * @var
     */
    protected $shippingAddress;

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return mixed
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param mixed $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * @return mixed
     */
    public function getPeriodDate()
    {
        return $this->periodDate;
    }

    /**
     * @param mixed $periodDate
     */
    public function setPeriodDate($periodDate)
    {
        $this->periodDate = $periodDate;
    }

    /**
     * @return mixed
     */
    public function getPeriodDays()
    {
        return $this->periodDays;
    }

    /**
     * @param mixed $periodDays
     */
    public function setPeriodDays($periodDays)
    {
        $this->periodDays = $periodDays;
    }

    /**
     * @return mixed
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param mixed $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * Create an instance of shipment
     *
     * @return Shipment
     */
    public function create()
    {
        return new Shipment($this->_em, $this->_class);
    }


    public function save(Customer $customer, CustomerShippingAddress $customerShippingAddress, Product $product, Token $token, Carrier $carrier, ShipmentStatus $shipmentStatus, User $manager){

        $shipment = $this->create();

        $shipment->setCustomer($customer);
        $shipment->setCustomerShippingAddress($customerShippingAddress);
        $shipment->setProduct($product);
        $shipment->setToken($token);
        $shipment->setCarrier($carrier);
        $shipment->setShipmentStatus($shipmentStatus);
        $shipment->setModifiedAt(new \DateTime);
        $shipment->setModifiedBy($manager);

        $this->_em->persist($shipment);
        $this->_em->flush();
    }
    /**
     * Get product by customer
     *
     * @param Product $product
     * @param Customer $customer
     * @return null|object
     */
    public function findProductCustomer(Product $product, Customer $customer){

        return $this->findOneBy(['product' => $product, 'customer' => $customer]);
    }

    /**
     * Find period orders
     *
     * @return $this
     */
    public function findPeriodOrders(){

        $today   = $this->getPeriodDate()->add(new \DateInterval('P1D'));

        $secs = $this->getPeriodDate()->getTimestamp() - ( 60 * 60 * 24 * $this->getPeriodDays() );
        $from       = new \DateTime(strftime('%Y-%m-%d %H:%M:%S', $secs));

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(s.id) AS total')
            ->from($this->getEntityName(), 's')
            ->where('s.active = 1')
            ->andWhere('s.customerShippingAddress = :customer_shipping_address')
            ->andWhere('s.modifiedAt >= :from')
            ->andWhere('s.modifiedAt <= :today')
            ->setParameter(':customer_shipping_address', $this->getShippingAddress())
            ->setParameter(':from', $from)
            ->setParameter(':today', $today)
            ->getQuery();

        $result = $qb->getScalarResult();

        $this->setOrders($result[0]['total']);

        return $this;
    }

    /**
     * Total Orders from findPeriodOrders method
     *
     * @return int
     */
    public function getTotalOrders(){

        return $this->getOrders();
    }


}
