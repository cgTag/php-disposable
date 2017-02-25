<?php
namespace cgTag\Test\TestCase;

use cgTag\Disposable\Dispose;
use cgTag\Disposable\Handlers\DisposeHandler;
use cgTag\Disposable\Handlers\IDisposeHandler;
use cgTag\Disposable\Test\TestCase\BaseTestCase;

class DisposeTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldForwardObjectCall()
    {
        $value = new \StdClass();

        $mock = $this->getMockBuilder(IDisposeHandler::class)->setMethods(['object', 'property', 'properties'])->getMock();
        $mock->expects($this->once())->method('object')->with($value);
        $mock->expects($this->never())->method('property');
        $mock->expects($this->never())->method('properties');

        DisposeHandler::setInstance($mock);
        Dispose::object($value);
    }

    /**
     * @test
     * @dataProvider shouldForwardPropertiesCallData
     * @param bool $allowArrays
     */
    public function shouldForwardPropertiesCall(bool $allowArrays)
    {
        $value = new \StdClass();

        $mock = $this->getMockBuilder(IDisposeHandler::class)->setMethods(['object', 'property', 'properties'])->getMock();
        $mock->expects($this->never())->method('object');
        $mock->expects($this->never())->method('property');
        $mock->expects($this->once())->method('properties')->with($value, $allowArrays)->willReturn(5);

        DisposeHandler::setInstance($mock);
        $result = Dispose::properties($value, $allowArrays);
        $this->assertSame(5, $result);
    }

    /**
     * @return array
     */
    public function shouldForwardPropertiesCallData(): array
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * @test
     */
    public function shouldForwardPropertyCall()
    {
        $value = new \StdClass();

        $mock = $this->getMockBuilder(IDisposeHandler::class)->setMethods(['object', 'property', 'properties'])->getMock();
        $mock->expects($this->never())->method('object');
        $mock->expects($this->once())->method('property')->with($value, 'title');
        $mock->expects($this->never())->method('properties');

        DisposeHandler::setInstance($mock);
        Dispose::property($value, 'title');
    }
}
