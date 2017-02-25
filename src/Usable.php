<?php
namespace cgTag\Disposable;

/**
 * Default implication of the using() function.
 */
final class Usable implements IUsable
{
    /**
     * @var IUsable
     */
    public static $_instance = null;

    /**
     * Gets the global instance.
     *
     * @return IUsable
     */
    public static function getInstance(): IUsable
    {
        if (static::$_instance === null) {
            static::setInstance(new Usable());
        }
        return static::$_instance;
    }

    /**
     * Sets the global instance (used for testing).
     *
     * @param IUsable $instance
     */
    public static function setInstance(IUsable $instance = null)
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
