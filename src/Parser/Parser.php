<?php

namespace ADB\ImmoSyncWhise\Parser;

use Psr\Log\LoggerInterface;

abstract class Parser
{
    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
