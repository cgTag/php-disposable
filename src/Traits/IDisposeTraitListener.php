<?php
namespace cgTag\Disposable\Traits;

/**
 * Optional interface on objects that use the DisposeTrait to enable callback events at the time of disposing.
 */
interface IDisposeTraitListener
{
    /**
     * Post disposable callback.
     *
     * @return void
     */
    public function afterDispose();

    /**
     * Pre-dispose callback. Returning false tells the trait to not auto dispose of anything.
     *
     * @return bool
     */
    public function beforeDispose(): bool;

    /**
     * Should the trait dispose of properties that are arrays. True to dispose recursively and false to ignore
     * arrays.
     *
     * @return bool
     */
    public function disposeArrays(): bool;
}
