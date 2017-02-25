<?php
namespace cgTag\Disposable\Test\TestCase\Handlers;

use cgTag\Disposable\Handlers\DisposeHandler;
use cgTag\Disposable\Test\Mocks\MockObjectWithProperty;
use cgTag\Disposable\Test\TestCase\BaseTestCase;

class DisposeHandlerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldHandlePropertiesThatAreNull()
    {
        $mock = new MockObjectWithProperty();
        $mock->property = null;

        $handler = new DisposeHandler();
        $handler->property($mock, 'property');
    }

    /**
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\NotAnObjectException
     * @expectedExceptionMessage Dispose requires a reference to an object
     */
    public function shouldThrowOnNullObject()
    {
        $handler = new DisposeHandler();
        $handler->property(null, 'property');
    }
}
