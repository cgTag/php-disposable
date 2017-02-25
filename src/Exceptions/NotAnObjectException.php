<?php
namespace cgTag\Disposable\Exceptions;

class NotAnObjectException extends DisposableException
{
    public function __construct($message = "Dispose requires a reference to an object", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
