<?php
namespace cgTag\Disposable\Test\TestCase;

use function cgTag\Disposable\using;

class DisposableTest extends DisposeTestCase
{
    /**
     * @test
     */
    public function shouldCallDispose()
    {
        $mock = $this->getMockDisposable();
        $mock->expects($this->once())->method('dispose');

        using($mock, $this->noop);
    }

    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage foobar
     */
    public function shouldCallDisposeOnException()
    {
        $mock = $this->getMockDisposable();
        $mock->expects($this->once())->method('dispose');

        using($mock, function(){
            throw new \Exception('foobar');
        });
    }
}
