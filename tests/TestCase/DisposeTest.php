<?php
namespace cgTag\Test\TestCase;

use cgTag\Disposable\Dispose;
use cgTag\Disposable\Test\Mocks\MockObjectWithProperty;
use cgTag\Disposable\Test\TestCase\BaseTestCase;

class DisposeTest extends BaseTestCase
{
    /**
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\NotAnObjectException
     * @expectedExceptionMessage Dispose requires a reference to an object
     */
    public function shouldThrowOnNullObject()
    {
        Dispose::property(null, 'property');
    }

    /**
     * @test
     */
    public function shouldHandlePropertiesThatAreNull()
    {
        $mock = new MockObjectWithProperty();
        $mock->property = null;
        Dispose::property($mock, 'property');
    }
}
