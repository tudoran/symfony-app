<?php

namespace BackOfficeBundle\Exceptions;

use JMS\Serializer\SerializerBuilder;

final class BackOfficeBaseException extends \Exception implements \Serializable
{

    /**
     * Backoffice Base Exception
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 404, Exception $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->getTraceAsString();
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize(get_object_vars($this), 'json');
    }

    /**
     * @param string $serialized
     * @return mixed
     */
    public function unserialize($serialized)
    {

        $serializer = SerializerBuilder::create()->build();

        return $serializer->deserialize($serialized, BackOfficeBaseException::class, 'json');
    }


}