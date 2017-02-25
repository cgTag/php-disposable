<?php
namespace cgTag\Disposable\Test\Mocks;

use cgTag\Disposable\IDisposable;

class MockDisposable implements IDisposable
{
    /**
     * Number of times dispose is called.
     *
     * @var int
     */
    public $count = 0;

    /**
     * Call dispose() on child properties.
     * Call unset() on object properties to reduce memory leaks.
     */
    public function dispose()
    {
        $this->count++;
    }
}
