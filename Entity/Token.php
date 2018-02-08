<?php

namespace BackOfficeBundle\Entity;

use BackOfficeBundle\Repository\TokenRepository;
use JMS\Serializer\SerializerBuilder;

class Token extends TokenRepository
{
    /**
     * @var integer
     */
    private $id;


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
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\CustomerShippingAddress
     */
    private $customerShippingAddress;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;

    /**
     * @var \BackOfficeBundle\Entity\Product
     */
    private $product;


    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Token
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
     * Set customerShippingAddress
     *
     * @param \BackOfficeBundle\Entity\CustomerShippingAddress $customerShippingAddress
     *
     * @return Token
     */
    public function setCustomerShippingAddress(\BackOfficeBundle\Entity\CustomerShippingAddress $customerShippingAddress = null)
    {
        $this->customerShippingAddress = $customerShippingAddress;

        return $this;
    }

    /**
     * Get customerShippingAddress
     *
     * @return \BackOfficeBundle\Entity\CustomerShippingAddress
     */
    public function getCustomerShippingAddress()
    {
        return $this->customerShippingAddress;
    }

    /**
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return Token
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
     * Set product
     *
     * @param \BackOfficeBundle\Entity\Product $product
     *
     * @return Token
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
     *
     */
    public function serialize()
    {
        $serializer = SerializerBuilder::create()->build();

        return
            $serializer->serialize(get_object_vars($this), 'json');
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        //
    }


    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $ipv4;

    /**
     * @var integer
     */
    private $redeemed = '0';

    /**
     * @var integer
     */
    private $active = '1';


    /**
     * Set token
     *
     * @param string $token
     *
     * @return Token
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ipv4
     *
     * @param string $ipv4
     *
     * @return Token
     */
    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    /**
     * Get ipv4
     *
     * @return string
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }

    /**
     * Set redeemed
     *
     * @param integer $redeemed
     *
     * @return Token
     */
    public function setRedeemed($redeemed)
    {
        $this->redeemed = $redeemed;

        return $this;
    }

    /**
     * Get redeemed
     *
     * @return integer
     */
    public function getRedeemed()
    {
        return $this->redeemed;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Token
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
     * @var string
     */
    private $formData;


    /**
     * Set formData
     *
     * @param string $formData
     *
     * @return Token
     */
    public function setFormData($formData)
    {
        $this->formData = $formData;

        return $this;
    }

    /**
     * Get formData
     *
     * @return string
     */
    public function getFormData()
    {
        return $this->formData;
    }
    /**
     * @var \BackOfficeBundle\Entity\TrafficSource
     */
    private $trafficSource;


    /**
     * Set trafficSource
     *
     * @param \BackOfficeBundle\Entity\TrafficSource $trafficSource
     *
     * @return Token
     */
    public function setTrafficSource(\BackOfficeBundle\Entity\TrafficSource $trafficSource = null)
    {
        $this->trafficSource = $trafficSource;

        return $this;
    }

    /**
     * Get trafficSource
     *
     * @return \BackOfficeBundle\Entity\TrafficSource
     */
    public function getTrafficSource()
    {
        return $this->trafficSource;
    }
    /**
     * @var \BackOfficeBundle\Entity\Post
     */
    private $post;

    /**
     * Get post id
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->post->getLink();
    }

    /**
     * Set post
     *
     * @param \BackOfficeBundle\Entity\Post $post
     *
     * @return Token
     */
    public function setPost(\BackOfficeBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \BackOfficeBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
    /**
     * @var string
     */
    private $confirmedBy;


    /**
     * Set confirmedBy
     *
     * @param string $confirmedBy
     *
     * @return Token
     */
    public function setConfirmedBy($confirmedBy)
    {
        $this->confirmedBy = $confirmedBy;

        return $this;
    }

    /**
     * Get confirmedBy
     *
     * @return string
     */
    public function getConfirmedBy()
    {
        return $this->confirmedBy;
    }
    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $streetAddress = '';


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Token
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set streetAddress
     *
     * @param string $streetAddress
     *
     * @return Token
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
}
