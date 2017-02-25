<?php
namespace cgTag\Disposable\Test\TestCase;

use cgTag\Disposable\IDisposable;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Base class for all tests.
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * It does nothing.
     *
     * @var callable
     */
    public $noop;

    /**
     * Creates a mock object that implements the interface.
     *
     * @return PHPUnit_Framework_MockObject_MockObject|IDisposable
     */
    public function mustDisposeOnce()
    {
        $mock = $this->getMockBuilder(IDisposable::class)
            ->setMethods(['dispose'])
            ->getMock();
        $mock->expects($this->once())->method('dispose');
        return $mock;
    }

    /**
     *
     */
    public function setUp()
    {
        $this->noop = function () {

        };
        parent::setUp();
    }
}
