<?php
namespace cgTag\Disposable\Test\TestCase;

use cgTag\Disposable\Handlers\IUsingHandler;
use cgTag\Disposable\Handlers\UsingHandler;
use cgTag\Disposable\Test\Mocks\MockDisposable;
use PHPUnit_Framework_MockObject_MockObject;

class UsingTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldForwardArgumentsToUsable()
    {
        $dispose = new MockDisposable();
        $this->assertSame(0, $dispose->count);

        /** @var PHPUnit_Framework_MockObject_MockObject|IUsingHandler $mock */
        $mock = $this->getMockBuilder(IUsingHandler::class)
            ->setMethods(['using'])
            ->getMock();

        $mock->expects($this->once())
            ->method('using')
            ->with($dispose, $this->noop)
            ->willReturn('hello world');

        UsingHandler::setInstance($mock);
        $result = using($dispose, $this->noop);

        $this->assertSame('hello world', $result);
    }
}
