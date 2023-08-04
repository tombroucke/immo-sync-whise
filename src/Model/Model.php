<?php

namespace ADB\ImmoSyncWhise\Model;

use Psr\Log\LoggerInterface;

abstract class Model
{
    public $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
