<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Contracts\CommandContract;
use ADB\ImmoSyncWhise\Services\EstateFetchService;

class FetchAllCommand implements CommandContract
{
    public const COMMAND_NAME = 'iws fetch-all';

    public function __construct(private EstateFetchService $estateFetchService)
    {
    }

    /**
     * Run below command to activate:
     * 
     * wp iws fetch-all handle
     */
    public function handle(): void
    {
        $this->estateFetchService->fetchAll();
    }
}
