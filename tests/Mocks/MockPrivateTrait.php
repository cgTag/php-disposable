<?php
namespace cgTag\Disposable\Test\Mocks;

use cgTag\Disposable\IDisposable;
use cgTag\Disposable\Traits\DisposeTrait;

class MockPrivateTrait implements IDisposable
{
    use DisposeTrait;

    /**
     * @var MockDisposable
     */
    private $value;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->value = new MockDisposable();
    }
}