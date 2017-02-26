<?php
namespace cgTag\Disposable\Exceptions;

class PropertyParamException extends DisposableException
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = "Property parameter must be string or ReflectionProperty", \Exception $previous = null)
    {
        parent::__construct($message, $previous);
    }
}