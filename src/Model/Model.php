<?php

namespace Bruno\ImmoSyncWhise\Model;

use Monolog\Logger;

abstract class Model
{
    public $operationsLogger;

    public function __construct(Logger $operationsLogger)
    {
        $this->operationsLogger = $operationsLogger;
    }
}
