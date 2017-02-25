<?php
namespace cgTag\Disposable\Handlers;

use cgTag\Disposable\Exceptions\MissingPropertyException;
use cgTag\Disposable\Exceptions\NotAnObjectException;
use cgTag\Disposable\Exceptions\NotPublicPropertyException;
use cgTag\Disposable\Exceptions\StaticPropertyException;
use cgTag\Disposable\IDisposable;

final class DisposeHandler implements IDisposeHandler
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
     * @param mixed $_obj
     */
    public function object($_obj)
    {
        if ($_obj instanceof IDisposable) {
            $_obj->dispose();
        }
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
            $propertyName = $property->getName();
            $property->setAccessible(true);
            $propertyValue = $property->getValue($_this);
            if ($propertyValue instanceof IDisposable) {
                if ($property->isStatic()) {
                    throw new StaticPropertyException();
                }
                if (!$property->isPublic()) {
                    throw new NotPublicPropertyException($reflector->name, $propertyName);
                }
                DisposeHandler::property($_this, $propertyName);
                $count++;
            }
            if (is_array($propertyValue) && $disposeArrays) {
                array_walk_recursive($propertyValue, function ($item) use ($_this) {
                    if ($item === $_this) {
                        // prevents endless loop
                        return;
                    }
                    DisposeHandler::object($item);
                });
            }
        }

        unset($reflector);

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
     * @param string $property
     * @throws MissingPropertyException
     * @throws NotAnObjectException
     */
    public function property($_this, string $property)
    {
        if ($_this === null || !is_object($_this)) {
            throw new NotAnObjectException();
        }

        $reflectProp = new \ReflectionProperty(get_class($_this), $property);

//        if (!isset($_this->$property)) {
//            throw new MissingPropertyException($_this, $property);
//        }

        DisposeHandler::object($reflectProp->getValue($_this));

        $reflectProp->setValue($_this, null);
    }
}
