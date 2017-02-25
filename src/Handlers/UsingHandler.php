<?php
namespace cgTag\Disposable\Handlers;

use cgTag\Disposable\IDisposable;

/**
 * Default implication of the using() function.
 */
final class UsingHandler implements IUsingHandler
{
    /**
     * @var IUsingHandler
     */
    public static $_instance = null;

    /**
     * Gets the global instance.
     *
     * @return IUsingHandler
     */
    public static function getInstance(): IUsingHandler
    {
        if (static::$_instance === null) {
            static::setInstance(new UsingHandler());
        }
        return static::$_instance;
    }

    /**
     * Sets the global instance (used for testing).
     *
     * @param IUsingHandler $instance
     */
    public static function setInstance(IUsingHandler $instance = null)
    {
        static::$_instance = $instance;
    }

    /**
     * Wraps the callable in a try/finally before calling dispose()
     *
     * @param IDisposable $obj
     * @param callable $worker
     * @return mixed
     */
    public function using(IDisposable $obj, callable $worker)
    {
        try {
            return $worker($obj);
        } finally {
            $obj->dispose();
        }
    }
}
