<?php

namespace ADB\ImmoSyncWhise\Command\Contracts;

interface CommandContract
{
    public function handle($model);
}
