<?php
namespace cgTag\Disposable;

use cgTag\Disposable\Exceptions\MissingPropertyException;

final class Garbage
{
    /**
     * Calls dispose if the object is IDisposable.
     *
     * @param mixed $ref
     * @param string|null $property
     * @throws MissingPropertyException
     *
     * @todo What if $ref is null?
     * @todo What if $ref is not an object and $property is not null
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
            throw new MissingPropertyException($ref, $property);
        }

        if ($ref->$property instanceof IDisposable) {
            $ref->$property->dispose();
        }

        unset($ref->$property);
    }
}
