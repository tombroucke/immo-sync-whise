<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Services\EstateSyncService;

class Fetch
{
    public const COMMAND_NAME = 'iws fetch-all';

    private $estateSyncService;

    public function __construct(EstateSyncService $estateSyncService)
    {
        $this->estateSyncService = $estateSyncService;
    }

    /**
     *
     * wp iws fetch-all handle
     */
    public function handle(): void
    {
        $this->estateSyncService->syncAll();
    }

    /**
     *
     * wp iws fetch get
     */
    // public function get($args)
    // {
    //     \WP_CLI::log("Fetching estate with ID {$args[0]} from Whise API");
    //     $this->operationsLogger->info("Fetching estate with ID {$args[0]} from Whise API");

    //     $estate = $this->estateAdapter->get($args[0], ['LanguageId' => $_ENV['LANG']]);
    //     $this->handle($estate);

    //     \WP_CLI::success("Fetching successful");
    //     $this->operationsLogger->info("Fetching successful");
    // }
}
