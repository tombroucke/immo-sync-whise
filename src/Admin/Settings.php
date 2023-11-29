<?php

namespace ADB\ImmoSyncWhise\Admin;

use ADB\ImmoSyncWhise\Plugin;

class Settings
{
    public const PAGE_SLUG = 'immo-sync-whise-settings';
    public const OPTION_NAME = 'isw_options';

    public function __construct()
    {
        // Initialize plugin settings
        add_action('admin_init', function () {
            $this->initSettings();
        });

        // Add settings page
        add_action('admin_menu', function () {
            $this->addMenuPage();
        });
    }

    private static function getOptionName($option)
    {
        return sprintf('%s[%s]', self::OPTION_NAME, $option);
    }

    private static function getOptionValue($option)
    {
        return static::getSetting($option);
    }

    protected function initSettings(): void
    {
        register_setting(self::PAGE_SLUG, self::OPTION_NAME);

        add_settings_section(
            'iws_api_creds',
            __('WHISE Credentials', 'immo-sync-whise'),
            function ($args) {
                echo Plugin::render('settings/credentials', $args);
            },
            self::PAGE_SLUG
        );

        add_settings_field(
            'whise_user',
            __('WHISE User', 'immo-sync-whise'),
            static function ($args) {
                echo Plugin::render('settings/field/input', [
                    'atts' => [
                        'type' => 'text',
                        'name' => static::getOptionName('whise_user'),
                        'value' => static::getOptionValue('whise_user'),
                        'disabled' => static::findSettingValue('whise_password'),
                    ],
                    'args' => $args,
                    'description' => __('The username or email address used to authenticate with the Whise API.', 'immo-sync-whise'),
                ]);
            },
            self::PAGE_SLUG,
            'iws_api_creds',
            [
                'class' => 'wporg_row',
                'label_for' => 'whise_user',
            ]
        );

        add_settings_field(
            'whise_password',
            __('WHISE Password', 'immo-sync-whise'),
            static function ($args) {
                echo Plugin::render('settings/field/input', [
                    'atts' => [
                        'type' => 'password',
                        'name' => static::getOptionName('whise_password'),
                        'value' => static::getOptionValue('whise_password'),
                        'disabled' => static::findSettingValue('whise_password'),
                    ],
                    'args' => $args,
                    'description' => __('The password used to authenticate with the Whise API.', 'immo-sync-whise'),
                ]);
            },
            self::PAGE_SLUG,
            'iws_api_creds',
            [
                'class' => 'wporg_row',
                'label_for' => 'whise_password',
            ]
        );
    }

    public static function getSettings()
    {
        $settings = wp_parse_args(
            get_option(self::OPTION_NAME),
            [
                'whise_user' => '',
                'whise_password' => '',
                'api_token' => '',
                'test_api_token' => '',
            ]
        );
        foreach ($settings as $key => $value) {
            $settings[$key] = self::findSettingValue($key) ?? $value;
        }
        ray($settings);
        return $settings;
    }

    public static function getSetting($setting)
    {
        $settings = static::getSettings();
        
        return $settings[$setting] ?? null;
    }

    protected function addMenuPage(): void
    {
        add_options_page(
            __('Immo Sync Whise', 'immo-sync-whise'),
            __('Immo Sync Whise', 'immo-sync-whise'),
            'manage_options',
            self::PAGE_SLUG,
            static function () {
                if (!current_user_can('manage_options')) {
                    return;
                }

                echo Plugin::render('settings', []);
            }
        );
    }

    /**
     * Find the setting in WP config, server or env
     *
     * @return string|null
     */
    public static function findSettingValue(string $optionName) : ?string
    {
        $optionName = strtoupper($optionName);
        if (defined($optionName)) {
            return constant($optionName);
        }
        if (isset($_SERVER[$optionName])) {
            return $_SERVER[$optionName];
        }
        if (isset($_ENV[$optionName])) {
            return $_ENV[$optionName];
        }
        
        return null;
    }
}
