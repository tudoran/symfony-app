<?php

namespace BackOfficeBundle\Entity;

use Doctrine\ORM\EntityManager;

/**
 * Accounting
 */
class Accounting
{

    public $periods = [];
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $parameters;
    /**
     * @var string
     */
    private $snapshot;
    /**
     * @var integer
     */
    private $active = '1';
    /**
     * @var \DateTime
     */
    private $modifiedAt;
    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;
    /**
     *
     * Accounting Products
     *
     * @var array
     */
    private $products = [];
    /**
     * @var string
     */
    private $name;
    /**
     * @var \DateTime
     */
    private $fromDate;
    /**
     * @var \DateTime
     */
    private $toDate;
    /**
     * @var integer
     */
    private $occurrence;
    /**
     * @var string
     */
    private $pricing;

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
     * Get parameters
     *
     * @return string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set parameters
     *
     * @param string $parameters
     *
     * @return Accounting
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get snapshot
     *
     * @return string
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * Set snapshot
     *
     * @param string $snapshot
     *
     * @return Accounting
     */
    public function setSnapshot($snapshot)
    {
        $this->snapshot = $snapshot;

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
     * Set active
     *
     * @param integer $active
     *
     * @return Accounting
     */
    public function setActive($active)
    {
        $this->active = $active;

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
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Accounting
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

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
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return Accounting
     */
    public function setModifiedBy(\BackOfficeBundle\Entity\User $modifiedBy = null)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Accounting
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return Accounting
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return Accounting
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get occurrence
     *
     * @return integer
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * Set occurrence
     *
     * @param integer $occurrence
     *
     * @return Accounting
     */
    public function setOccurrence($occurrence)
    {
        $this->occurrence = $occurrence;

        return $this;
    }

    /**
     * Get pricing
     *
     * @return string
     */
    public function getPricing()
    {
        return $this->pricing;
    }

    /**
     * Set pricing
     *
     * @param string $pricing
     *
     * @return Accounting
     */
    public function setPricing($pricing)
    {
        $this->pricing = $pricing;

        return $this;
    }

    /**
     * Get product list
     *
     * @return array
     */
    public function getProducts(){

        return $this->products;
    }

    /**
     * Set product list
     *
     * @param array|null $products
     *
     * @throws \Exception
     */
    public function setProducts(array $products = null){

        if(is_null($products))
            throw new \Exception('No products found for report');

        $this->products = $products;
    }

    /**
     * Add product to report
     *
     * @param Product|null $product
     * @param User|null $user
     *
     * @return AccountingProduct
     *
     * @throws \Exception
     */
    public function addProduct(Product $product = null, User $user = null)
    {
        if(is_null($product))
            throw new \Exception('Product for report cannot be null');

            $accountingProduct = new AccountingProduct;
            $accountingProduct->setAccounting($this);
            $accountingProduct->setProduct(($product));
            $accountingProduct->setModifiedAt(new \DateTime());
            $accountingProduct->setModifiedBy($user);

        $this->products[] = $product;

        return $accountingProduct;
    }

    /**
     * Get report period
     *
     * @return array
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * Set report period
     *
     * @param \DateTime $period
     */
    public function setPeriods(\DateTime $period)
    {
        $this->periods[] = $period;
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
     * @return Accounting
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
     * @return Accounting
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
}
