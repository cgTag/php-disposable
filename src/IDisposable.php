<?php
namespace cgTag\Disposable;

/**
 * Inspired by the IDisposable interface from .NET
 */
interface IDisposable
{
    /**
     * Call dispose() on child properties.
     * Call unset() on object properties to reduce memory leaks.
     */
    public function dispose();
}

