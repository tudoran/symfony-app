<?php

namespace BackOfficeBundle\Entity;

/**
 * UserActions
 */
class UserActions
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
     * @var \BackOfficeBundle\Entity\User
     */
    private $user;


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
     * @return UserActions
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
     * @return UserActions
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
     * @return UserActions
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
     * @return UserActions
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
     * Set user
     *
     * @param \BackOfficeBundle\Entity\User $user
     *
     * @return UserActions
     */
    public function setUser(\BackOfficeBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackOfficeBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
