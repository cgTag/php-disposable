<?php
namespace cgTag\Disposable\Exceptions;

class NotPublicPropertyException extends PropertyException
{
    /**
     * @param string $className
     * @param string $propertyName
     * @param \Exception $previous
     */
    public function __construct(string $className, string $propertyName, \Exception $previous = null)
    {
        parent::__construct(
            $className,
            $propertyName,
            "Can not dispose of non-public property",
            $previous
        );
    }
}
