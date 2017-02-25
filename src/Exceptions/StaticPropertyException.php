<?php
namespace cgTag\Disposable\Exceptions;

use cgTag\Disposable\IDisposable;

class StaticPropertyException extends PropertyException
{
    /**
     * @param string $className
     * @param string $property
     * @param \Exception|null $previous
     */
    public function __construct(string $className, string $property, \Exception $previous = null)
    {
        parent::__construct(
            $className,
            $property,
            sprintf("Static property implements %s", IDisposable::class),
            $previous
        );
    }
}
