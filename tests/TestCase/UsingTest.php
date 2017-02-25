<?php
namespace cgTag\Disposable\Test\TestCase;

use cgTag\Disposable\IUsable;
use cgTag\Disposable\Test\Mocks\MockDisposable;
use cgTag\Disposable\Usable;
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

        /** @var PHPUnit_Framework_MockObject_MockObject|IUsable $mock */
        $mock = $this->getMockBuilder(IUsable::class)
            ->setMethods(['using'])
            ->getMock();

        $mock->expects($this->once())
            ->method('using')
            ->with($dispose, $this->noop)
            ->willReturn('hello world');

        Usable::setInstance($mock);
        $result = using($dispose, $this->noop);

        $this->assertSame('hello world', $result);
    }
}
