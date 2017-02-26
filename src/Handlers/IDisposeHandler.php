<?php
namespace cgTag\Disposable\Handlers;

use cgTag\Disposable\Exceptions\NotPublicPropertyException;
use cgTag\Disposable\Exceptions\StaticPropertyException;

interface IDisposeHandler
{
    /**
     * Simply calls dispose() of the passed value implements IDisposable.
     *
     * @param mixed $obj
     * @param bool $disposeArrays
     * @return bool
     */
    public function object($obj, bool $disposeArrays = true): bool;

    /**
     * Disposes the properties of an object, but not the object itself.
     *
     * @param mixed $_this
     * @param bool $disposeArrays
     * @throws NotPublicPropertyException
     * @throws StaticPropertyException
     * @return int Number of properties disposed.
     */
    public function properties($_this, bool $disposeArrays = true): int;

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
     * @param string|\ReflectionProperty $property
     * @param bool $disposeArrays
     * @return bool
     */
    public function property($_this, $property, bool $disposeArrays = true): bool;
}
