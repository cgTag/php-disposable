<?php
namespace cgTag\Disposable\Test\Mocks;

class MockWithProtected
{
    /**
     * @var MockDisposable
     */
    protected $value;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new MockDisposable();
    }
}