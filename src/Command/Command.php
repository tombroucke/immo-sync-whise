<?php

namespace Bruno\ImmoSyncWhise\Command;

use Bruno\ImmoSyncWhise\Adapter\EstateAdapter;
use Bruno\ImmoSyncWhise\Container;
use Bruno\ImmoSyncWhise\Model\Estate;
use Bruno\ImmoSyncWhise\Parser\EstateParser;
use Psr\Log\LoggerInterface;

abstract class Command
{
    protected Estate $estate;
    protected EstateAdapter $estateAdapter;
    protected EstateParser $estateParser;
    protected LoggerInterface $logger;
    protected LoggerInterface $operationsLogger;

    public function __construct(Container $container)
    {
        $this->estate = $container->make(Estate::class);
        $this->estateAdapter = $container->make(EstateAdapter::class);
        $this->estateParser = $container->make(EstateParser::class);
        $this->logger = $container->get('logger');
        $this->operationsLogger = $container->get('operations');
    }
}
