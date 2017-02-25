<?php
namespace cgTag\Disposable\Traits;

use cgTag\Disposable\Exceptions\NotPublicPropertyException;
use cgTag\Disposable\Exceptions\StaticPropertyException;
use cgTag\Disposable\Garbage;
use cgTag\Disposable\IDisposable;

/**
 * @mixin IDisposable
 */
trait DisposeTrait
{
    /**
     * Disposes of all public, protected and private properties.
     */
    public function dispose()
    {
        if ($this instanceof IDisposeTraitListener && $this->beforeDispose() === false) {
            return;
        }

        $disposeArrays = ($this instanceof IDisposeTraitListener)
            ? $this->disposeArrays()
            : true;

        $reflector = new \ReflectionClass($this);
        foreach ($reflector->getProperties() as $property) {
            $propertyName = $property->getName();
            $property->setAccessible(true);
            $propertyValue = $property->getValue($this);
            if ($propertyValue instanceof IDisposable) {
                if ($property->isStatic()) {
                    throw new StaticPropertyException();
                }
                if (!$property->isPublic()) {
                    throw new NotPublicPropertyException($reflector->name, $propertyName);
                }
            }
            if (is_array($propertyValue) && $disposeArrays) {
                array_walk_recursive($propertyValue, function ($item) {
                    Garbage::dispose($item);
                });
            }
            if (is_object($propertyValue) && $property->isPublic()) {
                Garbage::dispose($this, $propertyName);
            }
        }

        unset($reflector);

        if ($this instanceof IDisposeTraitListener) {
            $this->afterDispose();
        }
    }
}
