<?php
namespace cgTag\Disposable;

interface IUsable
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
