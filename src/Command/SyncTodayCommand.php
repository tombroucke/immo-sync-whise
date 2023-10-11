<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\Contracts\CommandContract;
use ADB\ImmoSyncWhise\Services\EstateSyncTodayService;

class SyncTodayCommand implements CommandContract
{
    public const COMMAND_NAME = 'iws sync-today';

    public function __construct(private EstateSyncTodayService $estateSyncTodayService)
    {
    }

    /**
     * Run below command to activate:
     *
     * wp iws sync-today handle
     */
    public function handle(): void
    {
        $this->estateSyncTodayService->sync();
    }
}
