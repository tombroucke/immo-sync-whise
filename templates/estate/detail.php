<?php

use ADB\ImmoSyncWhise\Database\Database;

global $post;

$details = (new Database())->get($post->ID)->groupBy('detail_group');

?>

<div id="accordion">
    <?php foreach ($details as $key => $detail) : ?>
        <h3><?php echo $detail[0]->detail_group ?></h3>
        <div class="accordion_detail_wrapper">
            <?php foreach ($detail->all() as $key => $field) : ?>
                <div class="accordion_detail_group">
                    <p class="detail_value" id="tagline-description"><?php echo ucwords($field->detail_label) ?></p>
                    <div class="accordion_detail_details">
                        <p id="tagline-description">
                            <span class="detail_type"><?php echo ucwords($field->detail_type) ?>: </span><span class="detail_value"><?php echo $field->detail_value ?></span>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>