<?php

namespace ADB\ImmoSyncWhise\Services\Contracts;

interface ServiceContract
{
    public function run($args, $assocArgs): void;
}
