<?php

namespace BackOfficeBundle\Entity;

/**
 * PostClick
 */
class PostClick
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
     * @var integer
     */
    private $total = '0';

    /**
     * @var \DateTime
     */
    private $modifiedAt;

    /**
     * @var \BackOfficeBundle\Entity\Post
     */
    private $post;


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
     * @return PostClick
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
     * Set total
     *
     * @param integer $total
     *
     * @return PostClick
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
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
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return PostClick
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
     * Set post
     *
     * @param \BackOfficeBundle\Entity\Post $post
     *
     * @return PostClick
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
}
