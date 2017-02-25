<?php
namespace cgTag\Disposable\Exceptions;

use Exception;

class NotPublicPropertyException extends DisposableException
{
    public function __construct(string $className, string $propertyName)
    {
        parent::__construct("Can not auto-dispose of non-public GemsDisposable property: {$className}::{$propertyName}");
    }

}
