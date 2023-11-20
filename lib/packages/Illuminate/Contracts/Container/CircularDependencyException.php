<?php

namespace ADB\ImmoSyncWhise\Vendor\Illuminate\Contracts\Container;

use Exception;
use ADB\ImmoSyncWhise\Vendor\Psr\Container\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
