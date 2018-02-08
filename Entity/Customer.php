<?php

namespace BackOfficeBundle\Entity;

use BackOfficeBundle\Repository\CustomerRepository;
use BackOfficeBundle\USPS\Address;
use BackOfficeBundle\USPS\AddressVerify;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Customer extends CustomerRepository implements \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $active;

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
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var integer
     */
    private $birthDay;

    /**
     * @var integer
     */
    private $birthMonth;

    /**
     * @var integer
     */
    private $birthYear;

    /**
     * @var integer
     */
    private $subscribedNewsletter = '0';

    /**
     * @var \DateTime
     */
    private $modifiedAt;

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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set streetAddress
     *
     * @param string $streetAddress
     *
     * @return Customer
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * Set email
     *
     * @param string $email
     *
     * @return Customer
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Customer
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
     * Set birthDay
     *
     * @param integer $birthDay
     *
     * @return Customer
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * Get birthDay
     *
     * @return integer
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * Set birthMonth
     *
     * @param integer $birthMonth
     *
     * @return Customer
     */
    public function setBirthMonth($birthMonth)
    {
        $this->birthMonth = $birthMonth;

        return $this;
    }

    /**
     * Get birthMonth
     *
     * @return integer
     */
    public function getBirthMonth()
    {
        return $this->birthMonth;
    }

    /**
     * Set birthYear
     *
     * @param integer $birthYear
     *
     * @return Customer
     */
    public function setBirthYear($birthYear)
    {
        $this->birthYear = $birthYear;

        return $this;
    }

    /**
     * Get birthYear
     *
     * @return integer
     */
    public function getBirthYear()
    {
        return $this->birthYear;
    }

    /**
     * Set subscribedNewsletter
     *
     * @param integer $subscribedNewsletter
     *
     * @return Customer
     */
    public function setSubscribedNewsletter($subscribedNewsletter)
    {
        $this->subscribedNewsletter = $subscribedNewsletter;

        return $this;
    }

    /**
     * Get subscribedNewsletter
     *
     * @return integer
     */
    public function getSubscribedNewsletter()
    {
        return $this->subscribedNewsletter;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Customer
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
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return Customer
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('firstName', new NotBlank(array(
            'message' => 'First name cannot be blank',
        )));

        $metadata->addPropertyConstraint('lastName', new NotBlank(array(
            'message' => 'Last name cannot be blank',
        )));

        $metadata->addPropertyConstraint('email', new NotBlank(array(
            'message' => 'Email cannot be blank',
        )));

        $metadata->addPropertyConstraint('streetAddress', new NotBlank(array(
            'message' => 'Street address cannot be blank',
        )));

        $metadata->addPropertyConstraint('city', new NotBlank(array(
            'message' => 'City cannot be blank',
        )));

        $metadata->addPropertyConstraint('state', new NotBlank(array(
            'message' => 'State  cannot be blank',
        )));

        $metadata->addPropertyConstraint('zipCode', new NotBlank(array(
            'message' => 'Zip  name cannot be blank',
        )));

        $metadata->addPropertyConstraint('email', new Email(array(
            'message' => 'Email must be correct',
        )));
    }

    public function validateDestination($uspsKey){

        $verification = new AddressVerify($uspsKey);

        $address = new Address();

        $address->setFirmName(null);
        $address->setApt(null);
        $address->setAddress($this->getStreetAddress());
        $address->setCity($this->getCity());
        $address->setState($this->getState());
        $address->setZip5($this->getZipCode());
        $address->setZip4('');
        $verification->addAddress($address);
        $verification->verify();
        $response = $verification->getArrayResponse();

        if (!$verification->isSuccess())
            throw new \Exception($verification->getErrorMessage());

        if (isset($response['AddressValidateResponse']['Address']['ReturnText']))
            throw new \Exception($response['AddressValidateResponse']['Address']['ReturnText']);

        return $address;
    }

    public function getDestination() {
        $address = new Address();

        $address->setFirmName(null);
        $address->setApt(null);
        $address->setAddress($this->getStreetAddress());
        $address->setCity($this->getCity());
        $address->setState($this->getState());
        $address->setZip5($this->getZipCode());
        $address->setZip4('');

        return $address;
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
     * @return Customer
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
     * @return Customer
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

//    /**
//     * @param Product $product
//     * @return bool
//     */
//    public function hasProductHistory(Product $product)
//    {
//        if(is_null($this->getId()))
//            return false;
//
//        return parent::findProductHistory($this, $product);
//    }
}
