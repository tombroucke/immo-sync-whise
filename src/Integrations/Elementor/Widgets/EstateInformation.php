<?php

namespace ADB\ImmoSyncWhise\Integrations\Elementor\Widgets;

use ADB\ImmoSyncWhise\Matchers\EstateMatcher;
use ADB\ImmoSyncWhise\Model\Estate;
use Illuminate\Container\Container;

class EstateInformation extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'estate-information-widget';
    }

    public function get_title()
    {
        return __('Estate Information', ' immo-sync-whise');
    }

    public function get_icon()
    {
        return 'eicon-wordpress';
    }

    public function get_categories()
    {
        return ['immo-sync-whise'];
    }

    protected function render()
    {
        if (is_singular('estate')) {
            global $post;

            $estate = Container::getInstance()->make(Estate::class);
            $matcher = Container::getInstance()->make(EstateMatcher::class);
            $fields = $estate->getMetaFields($post->ID);

            if (!empty($fields)) {
                echo '<div class="information_wrapper">';
                foreach ($fields as $key => $field) {
                    $showField = get_post_meta($post->ID, '_show' . $key, true);
                    if ($showField == 1) {
                        echo '<div class="information_group">';
                        echo '<p><strong><span>' . esc_html($matcher->getTitle($key)) . ':</span></strong> <span id="' . esc_attr($key) . '">' . esc_html($matcher->getField($key, $field)) . '</span></p>';
                        echo '<div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        }
    }

    protected function content_template()
    {
?>
        <div class="information_wrapper">

        </div>
<?php
    }
}
