<?php
namespace cgTag\Disposable\Exceptions;

class MissingPropertyException extends PropertyException
{
    /**
     * @param string $className
     * @param string $property
     * @param \Exception $previous
     */
    public function __construct(string $className, string $property, \Exception $previous = null)
    {
        parent::__construct(
            $className,
            $property,
            'Missing property',
            $previous
        );
    }
}
