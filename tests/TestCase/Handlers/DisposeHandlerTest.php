<?php
namespace cgTag\Disposable\Test\TestCase\Handlers;

use cgTag\Disposable\Handlers\DisposeHandler;
use cgTag\Disposable\Handlers\IDisposeHandler;
use cgTag\Disposable\Test\Mocks\MockDisposable;
use cgTag\Disposable\Test\Mocks\MockObjectWithProperty;
use cgTag\Disposable\Test\Mocks\MockProperties;
use cgTag\Disposable\Test\Mocks\MockWithPrivate;
use cgTag\Disposable\Test\Mocks\MockWithStatic;
use cgTag\Disposable\Test\TestCase\BaseTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DisposeHandlerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldCallDispose()
    {
        $handler = new DisposeHandler();
        $mock = new MockDisposable();
        $this->assertSame(0, $mock->count);

        $handler->object($mock);
        $this->assertSame(1, $mock->count);
    }

    /**
     * @test
     */
    public function shouldCreateNewInstance()
    {
        DisposeHandler::setInstance(null);
        $this->assertNull(DisposeHandler::$_instance);

        $this->assertInstanceOf(DisposeHandler::class, DisposeHandler::getInstance());
    }

    /**
     * @test
     */
    public function shouldDisposeEachProperty()
    {
        $mock = new MockProperties();
        $mock->prop1 = new MockDisposable();
        $mock->prop2 = new MockDisposable();
        $mock->prop3 = new MockDisposable();
        $mock->prop4 = new MockDisposable();

        /** @var DisposeHandler|PHPUnit_Framework_MockObject_MockObject $handler */
        $handler = $this->getMockBuilder(DisposeHandler::class)
            ->setMethodsExcept(['properties'])
            ->getMock();

        $handler->method('property')
            ->withConsecutive(
                [$this->identicalTo($mock), $this->equalTo('prop1')],
                [$this->identicalTo($mock), $this->equalTo('prop2')],
                [$this->identicalTo($mock), $this->equalTo('prop3')],
                [$this->identicalTo($mock), $this->equalTo('prop4')]
            );

        $result = $handler->properties($mock);
        $this->assertSame(4, $result);
    }

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
     */
    public function shouldIgnoreValuesThatAreNotDisposable()
    {
        $handler = new DisposeHandler();
        foreach ([0, null, [12, 3, 4], 'foobar', new \stdClass()] as $value) {
            $handler->object($value);
        }
    }

    /**
     * @test
     */
    public function shouldNotWalkArrays()
    {
        $handler = new DisposeHandler();

        $this->markTestSkipped();
    }

    /**
     * @test
     */
    public function shouldSetMockAsHandler()
    {
        $mock = $this->getMockBuilder(IDisposeHandler::class)->setMethods(['object', 'property', 'properties'])->getMock();
        $mock->expects($this->never())->method('object');
        $mock->expects($this->never())->method('property');
        $mock->expects($this->never())->method('properties');

        DisposeHandler::setInstance($mock);
        $this->assertSame($mock, DisposeHandler::$_instance);
        $this->assertSame($mock, DisposeHandler::getInstance());
    }

    /**
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\MissingPropertyException
     * @expectedExceptionMessage Missing property on stdClass::$title
     */
    public function shouldThrowMissingProperty()
    {
        $mock = new \stdClass();
        $handler = new DisposeHandler();
        $handler->property($mock, 'title');
    }

    /**
     * @param mixed $value
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\NotAnObjectException
     * @expectedExceptionMessage Dispose requires a reference to an object
     * @dataProvider shouldThrowNotAnObjectData
     */
    public function shouldThrowNotAnObject($value)
    {
        $handler = new DisposeHandler();
        $handler->property($value, 'title');
    }

    /**
     * @return array
     */
    public function shouldThrowNotAnObjectData(): array
    {
        return [
            [0],
            [null],
            ['foobar'],
            [[1, 2, 3, 4]]
        ];
    }

    /**
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\NotPublicPropertyException
     * @expectedExceptionMessage
     */
    public function shouldThrowNotPublic()
    {
        $handler = new DisposeHandler();
        $mock = new MockWithPrivate();
        $handler->property($mock, 'value');
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

    /**
     * @test
     * @expectedException \cgTag\Disposable\Exceptions\StaticPropertyException
     * @expectedExceptionMessage
     */
    public function shouldThrowStaticProperty()
    {
        $handler = new DisposeHandler();
        $mock = new MockWithStatic();
        MockWithStatic::$value = new MockDisposable();
        $handler->property($mock, 'value');
    }

    /**
     * @test
     */
    public function shouldWalkArrays()
    {
        $handler = new DisposeHandler();

        $this->markTestSkipped();
    }
}
