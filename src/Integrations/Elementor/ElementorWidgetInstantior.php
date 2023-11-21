<?php

namespace ADB\ImmoSyncWhise\Integrations\Elementor;

use ADB\ImmoSyncWhise\Integrations\Elementor\Widgets\EstateInformation;

class ElementorWidgetInstantior
{
    public $widgets = [];

    public function __construct()
    {
        add_action('elementor/widgets/register', [$this, 'registerWidget']);
        add_action('elementor/elements/categories_registered', [$this, 'registerCategory']);
    }

    public function registerWidget($widgets_manager)
    {
        $widgets_manager->register(new EstateInformation());
    }

    public function registerCategory($elements_manager)
    {
        $elements_manager->add_category(
            'immo-sync-whise',
            [
                'title' => __('Immo Sync Whise', 'immo-sync-whise'),
                'icon'  => 'fa fa-plug',
            ]
        );
    }
}
