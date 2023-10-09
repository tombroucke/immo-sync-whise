<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Api;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use ADB\ImmoSyncWhise\Services\EstateSyncService;
use League\Container\Container as LeagueContainer;
use League\Container\ReflectionContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Whise\Api\WhiseApi;

class Container
{
    private static $instance = null;

    private $container;

    private function __construct()
    {
        $this->container = new LeagueContainer();
        $this->container->delegate(new ReflectionContainer()); // Enabled autowiring
    }

    public static function getInstance(): Container
    {
        if (self::$instance == null) {
            self::$instance = new self();
            self::$instance->addDependencies();
        }

        return self::$instance;
    }

    public function addDependencies(): void
    {
        $operationsLogger = (new Logger('operations'))->pushHandler(new StreamHandler(__DIR__ . '/logs/operations.log', Level::Debug));
        $logger = (new Logger('normal_log'))->pushHandler(new StreamHandler(__DIR__ . '/logs/debug.log', Level::Debug));

        $estate = new Estate(logger: $logger);
        $estateParser = new EstateParser(logger: $operationsLogger);
        $estateAdapter = new EstateAdapter(
            new Api(
                connection: new WhiseApi(),
                whiseUser: Settings::getSetting('whise_user'),
                whisePassword: Settings::getSetting('whise_password')
            )
        );
        $estateSyncService = new EstateSyncService(estate: $estate, estateAdapter: $estateAdapter, estateParser: $estateParser);

        // Everything is configured with an anonymous function, this ensures lazy loading, which is more performant
        $dependencies = [
            'logger' => fn () => $logger,
            'operations' => fn () => $operationsLogger,
            Estate::class => fn () => $estate,
            EstateParser::class => fn () => $estateParser,
            EstateAdapter::class => fn () => $estateAdapter,
            EstateSyncService::class => fn () => $estateSyncService,
        ];

        foreach ($dependencies as $alias => $concrete) {
            $this->container->add($alias, $concrete);
        }
    }

    public function add(string $alias, $concrete)
    {
        $this->container->add($alias, $concrete);
    }

    public function get(string $alias)
    {
        return $this->container->get($alias);
    }

    public function make(string $alias)
    {
        return $this->container->get($alias);
    }
}
