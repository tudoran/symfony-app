<?php

namespace BackOfficeBundle\Entity;

/**
 * Notification
 */
class Notification
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $broadcast;

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var int
     */
    private $modifiedBy;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Notification
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set broadcast
     *
     * @param boolean $broadcast
     *
     * @return Notification
     */
    public function setBroadcast($broadcast)
    {
        $this->broadcast = $broadcast;

        return $this;
    }

    /**
     * Get broadcast
     *
     * @return bool
     */
    public function getBroadcast()
    {
        return $this->broadcast;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Notification
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
     * @param integer $modifiedBy
     *
     * @return Notification
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get modifiedBy
     *
     * @return int
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }
}
