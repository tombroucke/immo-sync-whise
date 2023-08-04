<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Admin\CPT\Estate;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Command\CommandHandler;
use ADB\ImmoSyncWhise\Container;

class Plugin
{
    private array $modules = [];

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'initModules']);
    }

    public function initModules()
    {
        $container = Container::getInstance();

        $this->modules = [
            new CommandHandler($container),
            new Settings($container),
            new Estate($container),
        ];
    }

    public static function render(string $template, $context = [])
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
