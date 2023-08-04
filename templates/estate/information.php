<?php

use ADB\ImmoSyncWhise\Container;
use ADB\ImmoSyncWhise\Model\Estate;
use ADB\ImmoSyncWhise\Model\EstateMatcher;

global $post;

$fields = Container::getInstance()->get(Estate::class)->getIwsMetaFields($post->ID);
$matcher = new EstateMatcher();

?>

<div class="information_wrapper">
    <?php foreach ($fields as $key => $field) : ?>
        <div class="information_group">
            <p>
                <strong><span><?php echo $matcher->getTitle($key) ?>:</strong></span> <span id="<?php echo $key ?>"><?php echo $matcher->getField($key, $field) ?></span>
            </p>
            <div>
                <p class="description" id="tagline-description"><?php echo $matcher->getDescription($key) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>