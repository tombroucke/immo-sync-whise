<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Adapter\EstateAdapter;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Api;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Parser\EstateParser;
use ADB\ImmoSyncWhise\Services\EstateFetchService;
use ADB\ImmoSyncWhise\Services\EstateSyncDeletedService;
use ADB\ImmoSyncWhise\Services\EstateSyncTodayService;
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
        $operations     = (new Logger('operations'))->pushHandler(new StreamHandler(__DIR__ . '/logs/operations.log',   Level::Debug));
        $logger         = (new Logger('normal_log'))->pushHandler(new StreamHandler(__DIR__ . '/logs/debug.log',        Level::Debug));

        $estate         = new Estate(logger: $operations);
        $estateParser   = new EstateParser(logger: $operations);
        $estateAdapter  = new EstateAdapter(
            new Api(connection: new WhiseApi(), whiseUser: Settings::getSetting('whise_user'), whisePassword: Settings::getSetting('whise_password'))
        );
        $estateFetchService         = new EstateFetchService(estate: $estate, estateAdapter: $estateAdapter, estateParser: $estateParser, logger: $operations);
        $estateSyncDeletedService   = new EstateSyncDeletedService(estate: $estate, estateAdapter: $estateAdapter, estateParser: $estateParser, logger: $operations);
        $estateSyncTodayService     = new EstateSyncTodayService(estate: $estate, estateAdapter: $estateAdapter, estateParser: $estateParser, logger: $operations);

        // Everything is configured as an anonymous function, this ensures lazy loading, which is more performant
        $dependencies = [
            'logger'                                => fn () => $logger,
            'operations'                            => fn () => $operations,

            Estate::class                           => fn () => $estate,
            EstateParser::class                     => fn () => $estateParser,
            EstateAdapter::class                    => fn () => $estateAdapter,
            EstateFetchService::class               => fn () => $estateFetchService,
            EstateSyncDeletedService::class         => fn () => $estateSyncDeletedService,
            EstateSyncTodayService::class           => fn () => $estateSyncTodayService,
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
