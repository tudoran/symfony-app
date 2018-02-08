<?php

namespace BackOfficeBundle\Entity;

/**
 * UserRoleActions
 */
class UserRoleActions
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $active = '1';

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\Actions
     */
    private $action;

    /**
     * @var \BackOfficeBundle\Entity\User
     */
    private $modifiedBy;

    /**
     * @var \BackOfficeBundle\Entity\UserRole
     */
    private $userRole;


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
     * Set active
     *
     * @param integer $active
     *
     * @return UserRoleActions
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
     * @return UserRoleActions
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
     * Set action
     *
     * @param \BackOfficeBundle\Entity\Actions $action
     *
     * @return UserRoleActions
     */
    public function setAction(\BackOfficeBundle\Entity\Actions $action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \BackOfficeBundle\Entity\Actions
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set modifiedBy
     *
     * @param \BackOfficeBundle\Entity\User $modifiedBy
     *
     * @return UserRoleActions
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
     * Set userRole
     *
     * @param \BackOfficeBundle\Entity\UserRole $userRole
     *
     * @return UserRoleActions
     */
    public function setUserRole(\BackOfficeBundle\Entity\UserRole $userRole = null)
    {
        $this->userRole = $userRole;

        return $this;
    }

    /**
     * Get userRole
     *
     * @return \BackOfficeBundle\Entity\UserRole
     */
    public function getUserRole()
    {
        return $this->userRole;
    }
}
