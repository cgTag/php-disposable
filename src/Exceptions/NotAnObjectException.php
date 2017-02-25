<?php
namespace cgTag\Disposable\Exceptions;

class NotAnObjectException extends DisposableException
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = "Dispose requires a reference to an object", \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

}
