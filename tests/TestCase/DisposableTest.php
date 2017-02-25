<?php
namespace cgTag\Disposable\Test\TestCase;

class DisposableTest extends DisposeTestCase
{
    /**
     * @test
     */
    public function shouldBeSameInstance()
    {
        $mock = $this->mustDisposeOnce();
        using($mock, function ($value) use ($mock) {
            $this->assertSame($value, $mock);
        });
    }

    /**
     * @test
     */
    public function shouldCallClosure()
    {
        $count = 0;
        using($this->mustDisposeOnce(), function () use (&$count) {
            $count++;
        });
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function shouldCallDispose()
    {
        using($this->mustDisposeOnce(), $this->noop);
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage foobar
     */
    public function shouldCallDisposeOnException()
    {
        using($this->mustDisposeOnce(), function () {
            throw new \Exception('foobar');
        });
    }

    /**
     * @test
     */
    public function shouldHaveClosureReturnValue()
    {
        $result = using($this->mustDisposeOnce(), function () {
            return "hello world!";
        });
        $this->assertSame("hello world!", $result);
    }
}
