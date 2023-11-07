<?php

use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Matchers\EstateMatcher;
use ADB\ImmoSyncWhise\Model\Estate;

global $post;

$fields = Container::getInstance()->get(Estate::class)->getMetaFields($post->ID);
$matcher = new EstateMatcher();

?>

<div class="information_wrapper">
    <?php
    foreach ($fields as $key => $field) {
        if (strpos($key, '_show_') === false) {
            $showField = get_post_meta($post->ID, '_show' . $key, true);
            $showIcon = '';

            if ($showField == 0) {
                $showIcon = '<i class="fas fa-eye-slash clickable-eye" data-meta-key="' . $key . '" data-meta-value="' . $showField . '"></i>';
            } elseif ($showField == 1) {
                $showIcon = '<i class="fas fa-eye clickable-eye" data-meta-key="' . $key . '" data-meta-value="' . $showField . '"></i>';
            }

            echo '<div class="information_group">';
            echo '<p>';
            echo '<strong><span>' . $matcher->getTitle($key) . ': </strong></span>';
            echo '<span id="' . $key . '">' . $matcher->getField($key, $field) . ' ' . $showIcon . '</span>';
            echo '</p>';
            echo '<div>';
            echo '<p class="description" id="tagline-description">' . $matcher->getDescription($key) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>