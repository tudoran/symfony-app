<?php

namespace BackOfficeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserActionsRepository extends EntityRepository
{

    public function getAllowedActionsByRole(int $roleId = null)
    {
        $allowedActions = $this->createQueryBuilder('u')
            ->where('u.user_id = :user_id')
            ->setParameter('user_id', $roleId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $allowedActions) {
            $message = sprintf(
                'Unable to find an active BackOfficeBundle:User object identified by "%s".',
                $roleId
            );
            throw new UsernameNotFoundException($message);
        }

        return $allowedActions;
    }

}