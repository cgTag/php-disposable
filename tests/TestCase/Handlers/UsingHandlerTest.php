<?php
namespace cgTag\Disposable\Test\TestCase\Handlers;

use cgTag\Disposable\Handlers\IUsingHandler;
use cgTag\Disposable\Handlers\UsingHandler;
use cgTag\Disposable\Test\TestCase\BaseTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class UsingHandlerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldBeSameInstance()
    {
        $mock = $this->mustDisposeOnce();
        $usable = new UsingHandler();
        $usable->using($mock, function ($value) use ($mock) {
            $this->assertSame($value, $mock);
        });
    }

    /**
     * @test
     */
    public function shouldCallClosure()
    {
        $count = 0;
        $usable = new UsingHandler();
        $usable->using($this->mustDisposeOnce(), function () use (&$count) {
            $count++;
        });
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldCallDispose()
    {
        $usable = new UsingHandler();
        $usable->using($this->mustDisposeOnce(), $this->noop);
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage foobar
     */
    public function shouldCallDisposeOnException()
    {
        $usable = new UsingHandler();
        $usable->using($this->mustDisposeOnce(), function () {
            throw new \Exception('foobar');
        });
    }

    /**
     * @test
     */
    public function shouldClearGlobal()
    {
        $this->assertInstanceOf(UsingHandler::class, UsingHandler::getInstance());

        UsingHandler::setInstance(null);
        $this->assertNull(UsingHandler::$_instance);
    }

    /**
     * @test
     */
    public function shouldHaveClosureReturnValue()
    {
        $usable = new UsingHandler();
        $result = $usable->using($this->mustDisposeOnce(), function () {
            return "hello world!";
        });
        $this->assertSame("hello world!", $result);
    }

    /**
     * @test
     */
    public function shouldReplaceGlobalWithMockObject()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|IUsingHandler $mock */
        $mock = $this->getMockBuilder(IUsingHandler::class)->getMock();
        UsingHandler::setInstance($mock);

        $this->assertSame($mock, UsingHandler::getInstance());
    }
}
