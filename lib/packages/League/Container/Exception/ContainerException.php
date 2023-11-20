<?php

declare(strict_types=1);

namespace ADB\ImmoSyncWhise\Vendor\League\Container\Exception;

use ADB\ImmoSyncWhise\Vendor\Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
}
