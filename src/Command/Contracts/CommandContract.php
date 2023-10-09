<?php

namespace ADB\ImmoSyncWhise\Command\Contracts;

use ADB\ImmoSyncWhise\Model\Model;

interface CommandContract
{
    public function handle(Model $model): void;
}
