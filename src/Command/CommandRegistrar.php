<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\FetchAllCommand;
use ADB\ImmoSyncWhise\Command\SyncDeletedCommand;
use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Services\EstateFetchService;
use ADB\ImmoSyncWhise\Services\EstateSyncDeletedService;
use ADB\ImmoSyncWhise\Services\EstateSyncTodayService;

class CommandRegistrar
{
    public function __construct(public Container $container)
    {
        if (!defined('WP_CLI') || !WP_CLI) return;

        \WP_CLI::add_command(FetchAllCommand::COMMAND_NAME,     new FetchAllCommand($this->container->get(EstateFetchService::class)));
        \WP_CLI::add_command(SyncDeletedCommand::COMMAND_NAME,  new SyncDeletedCommand($this->container->get(EstateSyncDeletedService::class)));
        \WP_CLI::add_command(SyncTodayCommand::COMMAND_NAME,    new SyncTodayCommand($this->container->get(EstateSyncTodayService::class)));
    }
}
