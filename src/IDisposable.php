<?php
namespace cgTag\Disposable;

use cgTag\Disposable\Exceptions\DisposableException;

/**
 * Inspired by IDisposable on .NET
 */
interface IDisposable
{
    /**
     * Call dispose() on child properties.
     * Call unset() on object properties to reduce memory leaks.
     */
    public function dispose();
}

/**
 * Wraps the callable in a try/finally before calling dispose()
 *
 * @param IDisposable $obj
 * @param callable $worker
 * @return mixed
 */
function using(IDisposable $obj, callable $worker)
{
    try {
        return $worker($obj);
    } finally {
        $obj->dispose();
    }
}

/**
 * Calls dispose if the object is GemsDisposable
 *
 * @param mixed $ref
 * @param string|null $property
 * @throws DisposableException
 */
function dispose($ref, $property = null)
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
