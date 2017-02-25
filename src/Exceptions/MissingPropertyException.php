<?php
namespace cgTag\Disposable\Exceptions;

class MissingPropertyException extends DisposableException
{
    /**
     * @param mixed $refObject
     * @param string $property
     */
    public function __construct($refObject, string $property)
    {
        parent::__construct(sprintf("Garbage::dispose(%s,'%s') called, but property does not exist on target object.", get_class($refObject), $property));
    }

}
