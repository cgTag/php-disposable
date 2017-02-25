<?php
namespace cgTag\Disposable;

final class Usable
{
    /**
     * Wraps the callable in a try/finally before calling dispose()
     *
     * @param IDisposable $obj
     * @param callable $worker
     * @return mixed
     */
    public static function using(IDisposable $obj, callable $worker)
    {
        try {
            return $worker($obj);
        } finally {
            $obj->dispose();
        }
    }
}
