# php-disposable

[![License](https://poser.pugx.org/cgtag/php-disposable/license)](https://packagist.org/packages/cgtag/php-disposable)
[![Build Status](https://travis-ci.org/cgTag/php-disposable.svg?branch=master)](https://travis-ci.org/cgTag/php-disposable)
[![codecov](https://codecov.io/gh/cgTag/php-disposable/branch/master/graph/badge.svg)](https://codecov.io/gh/cgTag/php-disposable)
[![Total Downloads](https://poser.pugx.org/cgtag/php-disposable/downloads)](https://packagist.org/packages/cgtag/php-disposable)
[![Latest Stable Version](https://poser.pugx.org/cgtag/php-disposable/v/stable)](https://packagist.org/packages/cgtag/php-disposable)

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
use cgTag\Disposable\IDisposable;

class ResourceHolder implements IDisposable {
    
    private $file;
    
    public function __constructor(string $filename) {
        $this->file = fopen($filename, "r");
    }
    
    public function read() {
        return stream_get_contents($this->file);
    }
    
    public function dispose() {
        if($this->file) {
            fclose($this->file);
        }
        $this->file = null;
    }
}
```
Now you can use the global `using` function to dispose of the object when you're
finished with it.

Here's an example of the `using` function:

```
$content = using(new ResourceHolder(), function($resource) {
    return $resource->read();
});
```

That might look simple but once you start following the disposable pattern memory leaks are
going to be a thing of the past.

## dispose()

Make sure to propagate calls to `dispose()` to inherited classes. You can do this by
overriding the `dispose()` method and making sure to call `parent::dispose()`.

> Note: `dispose()` will only ever be called once. It will be the last method executed on a class before `__destruct()`
is called. You don't have to worry about properties being used afterwards as `dispose()` is
called.

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
use cgTag\Disposable\IDisposable;
use cgTag\Disposable\Traits\DisposeTrait;

class Service implements IDispose {
    use DisposeTrait;
    
    public $reader;
    
    public function __constructor() {
        $this->reader = new ConfigReader("config.ini");
    }
}
```

### Privates And Memory Leaks
The `DisposeTrait` can not dispose of private properties, but will throw an exception when a private property
references an object that implements the `IDisposable` interface. The trait does this because it found a possible
memory leak. Since the object is using the trait and private properties are not supported it means that the property
might not be disposed.

To resolve this conflict make the property public or implement the `dispose()` method on the object.

##  License
php-disposable is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
