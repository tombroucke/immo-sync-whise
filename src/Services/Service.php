<?php

namespace ADB\ImmoSyncWhise\Services;

use ADB\ImmoSyncWhise\Container;
use Psr\Log\LoggerInterface;

abstract class Service
{
    protected LoggerInterface $logger;
    protected LoggerInterface $operationsLogger;

    public function __construct(Container $container)
    {
        $this->logger           = $container->get('logger');
        $this->operationsLogger = $container->get('operations');
    }
}
