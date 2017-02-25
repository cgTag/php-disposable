<?php
namespace cgTag\Disposable\Test\TestCase;

use cgTag\Disposable\IDisposable;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DisposeTestCase extends TestCase
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
    public function getMockDisposable()
    {
        return $this->getMockBuilder(IDisposable::class)
            ->setMethods(['dispose'])
            ->getMock();
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