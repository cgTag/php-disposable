<?php
namespace cgTag\Disposable\Test\Mocks;

use cgTag\Disposable\IDisposable;
use cgTag\Disposable\Traits\DisposeTrait;

/**
 * An object that uses the trait.
 */
class MockDisposableTrait implements IDisposable
{
    use DisposeTrait;
}
