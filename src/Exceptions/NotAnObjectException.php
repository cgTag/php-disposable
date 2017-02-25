<?php
namespace cgTag\Disposable\Exceptions;

class NotAnObjectException extends DisposableException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "Dispose requires a reference to an object", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
