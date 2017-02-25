<?php
namespace cgTag\Disposable\Test\TestCase\Traits;

use cgTag\Disposable\Handlers\DisposeHandler;
use cgTag\Disposable\Handlers\IDisposeHandler;
use cgTag\Disposable\Test\Mocks\MockDisposableTrait;
use cgTag\Disposable\Test\TestCase\BaseTestCase;

class DisposeTraitTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldCallPropertiesOnHandler()
    {
        $trait = new MockDisposableTrait();

        $mock = $this->getMockBuilder(IDisposeHandler::class)->setMethods(['object', 'property', 'properties'])->getMock();
        $mock->expects($this->never())->method('object');
        $mock->expects($this->never())->method('property');
        $mock->expects($this->once())->method('properties')->with($trait);

        DisposeHandler::setInstance($mock);
        $trait->dispose();
    }
}
