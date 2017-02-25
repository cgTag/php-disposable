<?php
namespace cgTag\Disposable\Exceptions;

class StaticPropertyException extends DisposableException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "Found a static property that implements IDisposable. This usage is not supported.", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
