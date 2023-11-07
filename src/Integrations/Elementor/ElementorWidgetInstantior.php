<?php

namespace ADB\ImmoSyncWhise\Integrations\Elementor;

use ADB\ImmoSyncWhise\Integrations\Elementor\Widgets\EstateInformation;

class ElementorWidgetInstantior
{
    public $widgets = [];

    public function __construct()
    {
        $this->widgets = [
            new EstateInformation(),
        ];

        add_action('elementor/elements/categories_registered', [$this, 'addImmoSyncWhiseWidgetCategory']);
    }

    public function addImmoSyncWhiseWidgetCategory($elements_manager)
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
