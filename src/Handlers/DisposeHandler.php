<?php
namespace cgTag\Disposable\Handlers;

use cgTag\Disposable\Exceptions\MissingPropertyException;
use cgTag\Disposable\Exceptions\NotAnObjectException;
use cgTag\Disposable\Exceptions\NotPublicPropertyException;
use cgTag\Disposable\Exceptions\PropertyParamException;
use cgTag\Disposable\Exceptions\StaticPropertyException;
use cgTag\Disposable\IDisposable;

class DisposeHandler implements IDisposeHandler
{
    /**
     * @var DisposeHandler|null
     */
    public static $_instance = null;

    /**
     * @return IDisposeHandler
     */
    public static function getInstance(): IDisposeHandler
    {
        if (static::$_instance === null) {
            static::setInstance(new DisposeHandler());
        }
        return static::$_instance;
    }

    /**
     * @param IDisposeHandler|null $instance
     */
    public static function setInstance(IDisposeHandler $instance = null)
    {
        static::$_instance = $instance;
    }

    /**
     * Simply calls dispose() of the passed value implements IDisposable.
     *
     * @param mixed $obj
     * @param bool $disposeArrays
     * @return bool
     */
    public function object($obj, bool $disposeArrays = true): bool
    {
        $result = false;

        if ($obj instanceof IDisposable) {
            if (!isset($obj->{"IDisposableCalled"})) {
                $obj->dispose();
                $obj->{"IDisposableCalled"} = true;
                $result = true;
            }
        } elseif (is_array($obj) && $disposeArrays) {
            array_walk_recursive($obj, function ($item) use (&$result) {
                $result |= $this->object($item, true);
            });
        }

        return $result;
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
    public function properties($_this, bool $disposeArrays = true): int
    {
        $count = 0;

        $reflector = new \ReflectionClass($_this);
        foreach ($reflector->getProperties() as $property) {
            if ($this->property($_this, $property, $disposeArrays)) {
                $count++;
            }
        }

        return $count;
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
     * @param string|\ReflectionProperty $property
     * @param bool $disposeArrays
     * @return bool
     * @throws NotAnObjectException
     * @throws NotPublicPropertyException
     * @throws PropertyParamException
     * @throws StaticPropertyException
     * @throws MissingPropertyException
     */
    public function property($_this, $property, bool $disposeArrays = true): bool
    {
        if ($_this === null || !is_object($_this)) {
            throw new NotAnObjectException();
        }

        if (!is_string($property) && !$property instanceof \ReflectionProperty) {
            throw new PropertyParamException();
        }

        $className = get_class($_this);
        $reflectProp = is_string($property)
            ? $this->getReflectionProperty($className, $property)
            : $property;

        if ($reflectProp->isStatic()) {
            throw new StaticPropertyException($className, $property);
        }

        if (!$reflectProp->isPublic()) {
            throw new NotPublicPropertyException($className, $property);
        }

        try {
            return $this->object($reflectProp->getValue($_this), $disposeArrays);
        } finally {
            $reflectProp->setValue($_this, null);
        }
    }

    /**
     * @param string $className
     * @param string $property
     * @return \ReflectionProperty
     * @throws MissingPropertyException
     */
    private function getReflectionProperty(string $className, string $property): \ReflectionProperty
    {
        try {
            return new \ReflectionProperty($className, $property);
        } catch (\ReflectionException $ex) {
            throw new MissingPropertyException($className, $property, $ex);
        }
    }
}
