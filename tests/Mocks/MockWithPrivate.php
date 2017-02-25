<?php
namespace cgTag\Disposable\Test\Mocks;

class MockWithPrivate
{
    /**
     * @var MockDisposable
     */
    private $value;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new MockDisposable();
    }
}