<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
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
