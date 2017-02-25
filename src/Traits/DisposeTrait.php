<?php
namespace cgTag\Disposable\Traits;

use function cgTag\Disposable\dispose;
use cgTag\Disposable\Exceptions\DisposableException;
use cgTag\Disposable\IDisposable;

/**
 * @mixin IDisposable
 */
trait DisposeTrait
{
    /**
     * @var bool Set to false to disable disposing of array properties.
     */
    private $dispose_arrays = true;

    /**
     * Disposes of all public, protected and private properties.
     */
    public function dispose()
    {
        $reflector = new \ReflectionClass($this);
        foreach ($reflector->getProperties() as $property) {
            $name = $property->getName();
            $property->setAccessible(true);
            $value = $property->getValue($this);
            if ($value instanceof IDisposable) {
                if ($property->isStatic()) {
                    throw new DisposableException("Found a static property that implements GemsDisposable. This usage is not supported.");
                }
                if (!$property->isPublic()) {
                    throw new DisposableException("Can not auto-dispose of non-public GemsDisposable property: {$reflector->name}::{$name}");
                }
            }
            if (is_array($value) && $this->dispose_arrays === true) {
                array_walk_recursive($value, function ($item) {
                    dispose($item);
                });
            }
            if (is_object($value) && $property->isPublic()) {
                dispose($this, $name);
            }
        }

        // remove models added by GemsModelsTrait
        if (method_exists($this, 'disposeModels')) {
            $this->disposeModels();
        }

        unset($reflector);
    }
}
