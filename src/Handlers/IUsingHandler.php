<?php
namespace cgTag\Disposable\Handlers;

use cgTag\Disposable\IDisposable;

interface IUsingHandler
{
    /**
     * Wraps the callable in a try/finally before calling dispose()
     *
     * @param IDisposable $obj
     * @param callable $worker
     * @return mixed
     */
    public function using(IDisposable $obj, callable $worker);
}
