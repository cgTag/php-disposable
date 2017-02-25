<?php
namespace cgTag\Disposable\Test\TestCase;

use cgTag\Disposable\IUsable;
use cgTag\Disposable\Usable;
use PHPUnit_Framework_MockObject_MockObject;

class UsableTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldBeSameInstance()
    {
        $mock = $this->mustDisposeOnce();
        $usable = new Usable();
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
        $usable = new Usable();
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
        $usable = new Usable();
        $usable->using($this->mustDisposeOnce(), $this->noop);
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage foobar
     */
    public function shouldCallDisposeOnException()
    {
        $usable = new Usable();
        $usable->using($this->mustDisposeOnce(), function () {
            throw new \Exception('foobar');
        });
    }

    /**
     * @test
     */
    public function shouldClearGlobal()
    {
        $this->assertInstanceOf(Usable::class, Usable::getInstance());

        Usable::setInstance(null);
        $this->assertNull(Usable::$_instance);
    }

    /**
     * @test
     */
    public function shouldHaveClosureReturnValue()
    {
        $usable = new Usable();
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
        /** @var PHPUnit_Framework_MockObject_MockObject|IUsable $mock */
        $mock = $this->getMockBuilder(IUsable::class)->getMock();
        Usable::setInstance($mock);

        $this->assertSame($mock, Usable::getInstance());
    }
}
