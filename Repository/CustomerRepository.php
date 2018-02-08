<?php

namespace BackOfficeBundle\Repository;

use BackOfficeBundle\Entity\Customer;
use BackOfficeBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerRepository extends EntityRepository
{
    /**
     * @var ValidatorInterface
     */
    protected $_validator;

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    /**
     * @param $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->_validator = $validator;

        return $this;
    }


    /**
     * CustomerRepository constructor.
     * @param EntityManager $entityManager
     * @param Mapping\ClassMetadata $classMetadata
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $entityManager, Mapping\ClassMetadata $classMetadata, ValidatorInterface $validator)
    {
        $this->_validator = $validator;

        parent::__construct($entityManager, $classMetadata);
    }

    /**
     * Get instance of customer
     *
     * @return Customer
     */
    public function create()
    {

        return new Customer($this->_em, $this->_class, $this->getValidator());
    }

    /**
     * Handle write to database
     *
     * @param Customer $customer
     * @param ParameterBag $parameterBag
     * @param User $user
     * @return Customer
     * @throws \Exception
     */
    public function save(Customer $customer, ParameterBag $parameterBag, User $user){

        try {
            $customer->setFirstName($parameterBag->get('first_name'));
            $customer->setLastName($parameterBag->get('last_name'));
            $customer->setEmail($parameterBag->get('email'));
            $customer->setStreetAddress($parameterBag->get('street_address'));
            $customer->setCity($parameterBag->get('city'));
            $customer->setState($parameterBag->get('state'));
            $customer->setZipCode($parameterBag->get('zip_code'));
            $customer->setModifiedAt(new \DateTime());
            $customer->setModifiedBy($user);

            $violations = $customer->getValidator()->validate($customer);

            if ($violations->count()) {

                foreach ($violations as $violation) {
                    throw new \Exception('Customer validation error:' . $violation->getMessage());
                }
            }

            $this->_em->persist($customer);
            $this->_em->flush();

            return $customer;

        } catch(ORMException $e){

            throw new \Exception($e->getPrevious()->getMessage());
        } catch(\Exception $e){
            throw $e;
        }
    }

    /**
     * Find one customer by email or create new
     * @param $email
     * @return Customer
     */
    public function findOneByEmail($email){

        return $this->findOneBy(['email' => $email]);
    }


}
