<?php
namespace cgTag\Disposable;

use cgTag\Disposable\Exceptions\DisposableException;

final class Garbage
{
    /**
     * Calls dispose if the object is GemsDisposable
     *
     * @param mixed $ref
     * @param string|null $property
     * @throws DisposableException
     */
    public static function dispose($ref, $property = null)
    {
        if ($property === null) {
            if ($ref instanceof IDisposable) {
                $ref->dispose();
            }
            return;
        }

        if (!isset($ref->$property)) {
            throw new DisposableException(sprintf("dispose(%s,'%s') called, but property does not exist on target object.", get_class($ref), $property));
        }

        if ($ref->$property instanceof IDisposable) {
            $ref->$property->dispose();
        }

        unset($ref->$property);
    }
}
