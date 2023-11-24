<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Admin\CPT\EstateCPT;
use ADB\ImmoSyncWhise\Admin\Settings;
use ADB\ImmoSyncWhise\Command\CommandRegistrar;
use ADB\ImmoSyncWhise\Integrations\Elementor\ElementorWidgetInstantior;
use Illuminate\Container\Container;

class Plugin extends Container
{
    public function __construct(private array $modules = [])
    {
        add_action('plugins_loaded', [$this, 'initModules']);
    }

    public function initModules(): void
    {
        $this->modules = [
            new CommandRegistrar($this),
            new Settings(),
            new EstateCPT(),
        ];

        add_action('elementor/init', [$this, 'initElementor']);
    }

    public function initElementor()
    {
        class_exists('\Elementor\Widget_Base') ? new ElementorWidgetInstantior() : null;
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
