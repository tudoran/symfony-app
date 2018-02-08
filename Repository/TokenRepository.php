<?php

namespace BackOfficeBundle\Repository;

use BackOfficeBundle\Entity\Post;
use BackOfficeBundle\Entity\Token;
use BackOfficeBundle\Entity\TrafficSource;
use BackOfficeBundle\Entity\User;
use BackOfficeBundle\Helper\BaseHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Symfony\Component\HttpFoundation\Request;

class TokenRepository extends EntityRepository
{
    /**
     * TokenRepository constructor.
     * @param EntityManager $entityManager
     * @param Mapping\ClassMetadata $classMetadata
     */
    public function __construct(EntityManager $entityManager, Mapping\ClassMetadata $classMetadata)
    {
        parent::__construct($entityManager, $classMetadata);

    }

    /**
     * @param Request $parameterBag
     * @param string $ipClient
     * @return Token
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function save(Request $parameterBag, $ipClient = '')
    {

        $token = new Token($this->_em, $this->_class);

        $token->setFormData(BaseHelper::serialize($parameterBag->request->all()));
        $token->setIpv4($ipClient);
        $token->setToken('::');
        $token->setEmail($parameterBag->get('email'));
        $token->setStreetAddress($parameterBag->get('street_address'));
        $token->setPost($this->_em->getRepository(Post::class)->findOneBy(['link' => $parameterBag->get('post_id')]));
        $token->setTrafficSource($this->_em->find(TrafficSource::class, $parameterBag->get('traffic_source_id')));
        $token->setModifiedAt(new \DateTime);
        $token->setModifiedBy($this->_em->find(User::class,1));
        $this->_em->persist($token);
        $this->_em->flush();

        return $token;
    }

    /**
     * Find token by token string
     * @param string $input
     * @return Token
     * @throws \Exception
     */
    public function findTokenByTokenOrFail($input = ''){

        $token =  $this->findOneBy(['token' => $input]);

        if(is_null($token))
            throw new \Exception(sprintf("Token identified by %s does not exist", $input ));

        return $token;
    }

    /**
     * Deactivate
     * @param Token $token
     */
    public function deactivate(Token $token){

        $token->setActive(0);
        $this->_em->persist($token);
        $this->_em->flush();
    }
}
