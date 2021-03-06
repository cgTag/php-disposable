<?php
namespace cgTag\Disposable;


use cgTag\Disposable\Exceptions\MissingPropertyException;
use cgTag\Disposable\Exceptions\NotAnObjectException;
use cgTag\Disposable\Exceptions\NotPublicPropertyException;
use cgTag\Disposable\Exceptions\StaticPropertyException;
use cgTag\Disposable\Handlers\DisposeHandler;

/**
 * A convenience static implementation of IDispose
 */
final class Dispose
{
    /**
     * Simply calls dispose() of the passed value implements IDisposable.
     *
     * @param mixed $_obj
     */
    public static function object($_obj)
    {
        DisposeHandler::getInstance()->object($_obj);
    }

    /**
     * Disposes the properties of an object, but not the object itself.
     *
     * @param mixed $_this
     * @param bool $disposeArrays
     * @throws NotPublicPropertyException
     * @throws StaticPropertyException
     * @return int Number of properties disposed.
     */
    public static function properties($_this, bool $disposeArrays = true): int
    {
        return DisposeHandler::getInstance()->properties($_this, $disposeArrays);
    }

    /**
     * This function is a convenience method for disposing of properties. It's intended to be used inside the dispose()
     * method on IDisposable.
     *
     * # Example
     *
     * ```
     * class Service implements IDispose {
     *
     *      public $reader;
     *
     *      public $unknown;
     *
     *      public function __constructor($unknown) {
     *          $this->reader = new ReaderDisposable();
     *          $this->unknown = $unknown;
     *      }
     *
     *      public function dispose() {
     *          // this will dispose of the reader
     *          Dispose::property($this,'reader');
     *
     *          // this may dispose of unknown only if it implements IDisposable
     *          Dispose::property($this,'unknown');
     *      }
     * }
     * ```
     *
     * @param mixed $_this
     * @param string $property
     * @throws MissingPropertyException
     * @throws NotAnObjectException
     */
    public static function property($_this, string $property)
    {
        DisposeHandler::getInstance()->property($_this, $property);
    }
}
