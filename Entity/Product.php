<?php

namespace BackOfficeBundle\Entity;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Product
 */
class Product implements \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $active = '1';

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $sku;

    /**
     * @var
     */
    private $vendorLink;

    /**
     * @var
     */
    private $unitCost;

    /**
     * @var
     */
    private $shippingCost;

    /**
     * @var string
     */
    private $weight;

    /**
     * @var
     */
    private $weightMeasure;

    /**
     * @var integer
     */
    private $total;

    /**
     * @var integer
     */
    private $threshold;

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;

    /**
     * @var \BackOfficeBundle\Entity\Vendor
     */
    private $vendor;

    /**
     * @var
     */
    private $posts;


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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return Product
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendorLink()
    {
        return $this->vendorLink;
    }

    /**
     * @param mixed $vendorLink
     */
    public function setVendorLink($vendorLink)
    {
        $this->vendorLink = $vendorLink;
    }

    /**
     * Get Unit Cost
     *
     * @return string
     */
    public function getUnitCost()
    {
        return $this->unitCost;
    }

    /**
     * Set Unit Cost
     *
     * @param $unitCost
     *
     * @return $this
     */
    public function setUnitCost($unitCost)
    {
        $this->unitCost = $unitCost;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * @param mixed $shippingCost
     *
     * @return Product
     */
    public function setShippingCost($shippingCost)
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeightMeasure()
    {
        return $this->weightMeasure;
    }

    /**
     * @param mixed $weightMeasure
     */
    public function setWeightMeasure($weightMeasure)
    {
        $this->weightMeasure = $weightMeasure;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Product
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get threshold
     *
     * @return integer
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * Set threshold
     *
     * @param integer $threshold
     *
     * @return Product
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;

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
     * @return Product
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
     * @return Product
     */
    public function setModifiedBy(\BackOfficeBundle\Entity\User $modifiedBy = null)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return \BackOfficeBundle\Entity\Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set vendor
     *
     * @param \BackOfficeBundle\Entity\Vendor $vendor
     *
     * @return Product
     */
    public function setVendor(\BackOfficeBundle\Entity\Vendor $vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     */
    public function setPosts(Post $post)
    {
        $this->posts[] = $post;
    }

    /**
     * @param array $out
     * @return string
     */
    public function getPostsUrl($out = array()){

        foreach($this->posts as $post)
            $out[] = $post->getLink();


        return implode("\n", $out);
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
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
     * @return Product
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
     * @return Product
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

    /**
     * Add stock
     *
     * @param int $quantity
     */
    public function add($quantity = 0){

        $this->total += $quantity;
    }

    /**
     * Reduce stock
     *
     * @param int $quantity
     */
    public function reduce($quantity = 0)
    {
        $this->total -= $quantity;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('name', new Regex(array(
            'pattern' => '/^[a-zA-Za0-9\s_-]+$/',
            'message' => 'Product name should contain only alpha numeric characters. Additional: dash, underscore and whitespace are allowed'
        )));

        $metadata->addPropertyConstraint('vendorLink', new Url(array(
            'message' => 'Product link must start with http:// or https://'
        )));

        $metadata->addPropertyConstraint('weight', new Type(array(
            'type' => 'numeric',
            'message' => 'Product weight must be a numeric value, e.g. 10.10'
        )));

        $metadata->addPropertyConstraint('weight', new GreaterThanOrEqual(array(
            'value' => 0,
            'message' => 'Product weight must be positive number'
        )));

        $metadata->addPropertyConstraint('threshold', new Type(array(
            'type' => 'int',
            'message' => 'Product threshold must be a number'
        )));

        $metadata->addPropertyConstraint('threshold', new GreaterThanOrEqual(array(
            'value' => 0,
            'message' => 'Product threshold must be positive number'
        )));

        $metadata->addPropertyConstraint('total', new Type(array(
            'type' => 'int',
            'message' => 'Product total stock must be a number'
        )));

        $metadata->addPropertyConstraint('total', new GreaterThanOrEqual(array(
            'value' => 0,
            'message' => 'Product total stock must be positive number'
        )));

//        $metadata->addPropertyConstraint('total', new GreaterThanOrEqual(array(
//            'value' => self::getThreshold(),
//            'message' => 'Product total stock must be greater than product threshold alert'
//        )));
    }
}
