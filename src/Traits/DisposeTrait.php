<?php
namespace cgTag\Disposable\Traits;

use cgTag\Disposable\Handlers\DisposeHandler;
use cgTag\Disposable\IDisposable;

/**
 * @mixin IDisposable
 */
trait DisposeTrait
{
    /**
     * Disposes of all public properties, and optionally arrays.
     */
    public function dispose()
    {
        DisposeHandler::getInstance()->properties($this);
    }
}
