<?php

namespace ADB\ImmoSyncWhise\Parser;

use Monolog\Logger;

abstract class Parser
{
    public $operationsLogger;

    public function __construct(Logger $operationsLogger)
    {
        $this->operationsLogger = $operationsLogger;
    }
}
