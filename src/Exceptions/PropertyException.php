<?php
namespace cgTag\Disposable\Exceptions;

use Exception;

abstract class PropertyException extends DisposableException
{
    /**
     * @param string $className
     * @param string $property
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct(string $className, string $property, string $message, Exception $previous = null)
    {
        parent::__construct(sprintf('%s on %s::$%s', $message, $className, $property), 0, $previous);
    }
}