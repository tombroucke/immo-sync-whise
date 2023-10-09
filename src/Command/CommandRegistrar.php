<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\FetchAllCommand;
use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Services\EstateSyncService;

class CommandRegistrar
{
    public function __construct(public Container $container)
    {
        if (!defined('WP_CLI') || !WP_CLI) return;

        \WP_CLI::add_command(FetchAllCommand::COMMAND_NAME, new FetchAllCommand($this->container->get(EstateSyncService::class)));
    }
}
