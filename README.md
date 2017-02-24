# php-disposable [![Build Status](https://travis-ci.org/cgTag/php-disposable.svg?branch=master)](https://travis-ci.org/cgTag/php-disposable)
A tiny library that adds the disposable pattern to PHP

## Requirements
PHP 7.1 or above (at least PHP 7.1.0 for now)

## Install

You can easily install this library using [Composer](https://getcomposer.org/) with the following command:

```
    $ composer require cgtag/php-disposable
```

# Documentation
The basic instructions for using this library is implementing the `IDisposable` interface and
declaring the `dispose()` method that implements all the resource cleanup logic.

The following example shows a simple implementation of the basic pattern.

```
class DisposableResourceHolder implements IDisposable {
    
    private $file;
    
    public function __constructor(string $filename) {
        $this->file = fopen($filename, "r");
    }
    
    public function dispose() {
        if($this->file) {
            fclose($this->file);
        }
        $this->file = null;
    }
}
```

Make sure to propagate calls to `dispose()` to inherited classes. You can do this by
overriding the `dispose()` method and making sure to call `parent::dispose()`.

> Note: `dispose()` will only ever be called once. It will be the last method executed on a class before `__destruct()`
is called. You don't have to worry about properties being used as `dispose()` is called.

## The IDisposable Interface
This is the primary interface used by the library. In PHP the garbage collector automatically releases
memory allocated to a managed object when that object is no longer used. However, it is not possible
to predict when the garbage collection will occur. Furthermore, the garbage collector has no knowledge
of unmanaged resources such as file handles, images and streams.

Use the `dispose()` method if this interface to explicitly release unmanaged resources in conjunction
with the garbage collector. The consumer of an object can call this method when the object is no
longer needed.

## Global using() Function
Provides a convenience function that ensures the correct use of `IDisposable` objects. The following
example shows how to use the global `using()` function.

```
    using(new ConfigReader("config.ini"), function(ConfigReader $reader) {
        $debug = $reader->get('debug');
    });
```

The **using** function ensures that `dispose()` is called even if an exception occurs while you are
calling methods on the object. You can achieve the same result by putting the object inside a try
block and then calling `dispose()` in the finally block; in fact, this is how the **using** function
is written. The code example earlier could be written as the following example:

```
    $reader = new ConfigReader("config.ini");
    try {
        $debug = $reader->get('debug');
    } finally {
        $reader->dispose();
    }
```

You can instantiate the resource object and then pass the variable to the **using** function, but this
is not a best practice. In this case, the object remains in scope after control leaves the **using**
block even though it will probably no longer have access to its unmanaged resources. In other words, it
will no longer be fully initialized. If you try to use the object outside the **using** callback, you risk
causing an exception to be thrown. For this reason, it is generally better to instantiate the object as an
argument passed to **using** and limit its scope to the **using** callback.

 ```
    $reader = new ConfigReader("config.ini");
    using($reader, function(ConfigReader $reader) {
        // use reader
    });
    // reader is still in scope, but calling it throws an exception
    $debug = $reader->get('debug');
 ```

> Note: `using` should not be confused with `use`

## The DisposableTrait
The `DisposeTrait` allows an object to automatically dispose of public properties. When an object implements
the `IDisposable` interface and uses the `DisposeTrait` when that object is disposed then all the public properties
that reference an `IDisposable` object are also disposed of and unset. The trait also deeply walks all public arrays
disposing of any objects that are in those arrays.

Here is an example object that uses the `ConfigReader` from above examples:

```
class Service implements IDispose {
    use DisposeTrait;
    
    public $reader;
    
    public function __constructor() {
        $this->reader = new ConfigReader("config.ini");
    }
}
```

### Privates And Memory Leaks
The `DisposeTrait` can not dispose of private properties, but will throw an exception when a private provider
references an object that implements the `IDisposable` interface. The trait does this because it's found a possible
memory leak. Since the object is using the trait and private properties are not supported it means that object
might not be disposed.

To resolve this conflict make the property public or implement the `dispose()` method on the object.

##  License
php-disposable is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
