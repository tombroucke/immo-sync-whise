<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Admin\CPT\EstateCPT;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Command\CommandRegistrar;
use ADB\ImmoSyncWhise\Container;

class Plugin
{
    public function __construct(private array $modules = [])
    {
        add_action('plugins_loaded', [$this, 'initModules']);
    }

    public function initModules(): void
    {
        $container = Container::getInstance();

        $this->modules = [
            new CommandRegistrar($container),
            new Settings(),
            new EstateCPT(),
        ];
    }

    public static function render(string $template, array $context = []): string|null
    {
        $templateFolder = ISW_PATH . '/templates/';
        $templateFile = $templateFolder . $template . '.phtml';

        if (!is_readable($templateFile)) {
            return null;
        }

        extract($context, EXTR_SKIP);
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

    public static function isTestMode(): bool
    {
        return defined('IWS_TEST_MODE') && IWS_TEST_MODE === true;
    }

    public static function isDebugMode(): bool
    {
        return defined('IWS_DEBUG') && IWS_DEBUG === true;
    }
}
