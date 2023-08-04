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
        return wp_parse_args(
            get_option(self::OPTION_NAME),
            [
                'api_token' => '',
                'test_api_token' => '',
            ]
        );
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
}
