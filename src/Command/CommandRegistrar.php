<?php

namespace ADB\ImmoSyncWhise\Command;

use ADB\ImmoSyncWhise\Command\FetchAllCommand;
use ADB\ImmoSyncWhise\Command\SyncDeletedCommand;
use ADB\ImmoSyncWhise\Command\TestCommand;
use ADB\ImmoSyncWhise\Services\EstateFetchService;
use ADB\ImmoSyncWhise\Services\EstateSyncDeletedService;
use ADB\ImmoSyncWhise\Services\EstateSyncTodayService;
use ADB\ImmoSyncWhise\Services\TestService;
use Illuminate\Container\Container;

class CommandRegistrar
{
    protected array $commands = [
        FetchAllCommand::class => EstateFetchService::class,
        SyncDeletedCommand::class => EstateSyncDeletedService::class,
        SyncTodayCommand::class => EstateSyncTodayService::class,
        TestCommand::class => TestService::class,

        // Add more commands as needed...
    ];

    public function __construct(public Container $container)
    {
        if (!defined('WP_CLI') || !WP_CLI) return;

        foreach ($this->commands as $commandClass => $serviceClass) {
            \WP_CLI::add_command($commandClass::COMMAND_NAME, function () use ($commandClass, $serviceClass) {
                $serviceInstance = $this->container->make($serviceClass);
                $commandInstance = $this->container->make($commandClass, ['service' => $serviceInstance]);
                $commandInstance->handle();
            });
        }
    }
}
