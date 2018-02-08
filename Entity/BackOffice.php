<?php namespace BackOfficeBundle\Entity;



abstract class BackOffice implements \Serializable
{
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


}