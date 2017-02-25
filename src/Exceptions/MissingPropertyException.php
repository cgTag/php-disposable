<?php
namespace cgTag\Disposable\Exceptions;

class MissingPropertyException extends DisposableException
{
    /**
     * @param mixed $refObject
     * @param string $property
     * @param \Exception $previous
     */
    public function __construct($refObject, string $property, \Exception $previous = null)
    {
        parent::__construct(sprintf("Property \"%s\" does not exist on %s", $property, get_class($refObject)), 0, $previous);
    }

}
