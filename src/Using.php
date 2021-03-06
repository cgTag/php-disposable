<?php
use cgTag\Disposable\IDisposable;
use cgTag\Disposable\Handlers\UsingHandler;

/**
 * Prevents collision with other global functions.
 */
if (function_exists('using')) {
    return;
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
    return UsingHandler::getInstance()->using($obj, $worker);
}
