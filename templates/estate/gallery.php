<?php

use Bruno\ImmoSyncWhise\Model\Estate;

global $post;

$estate = (new Estate())->get_estate($post->ID);

?>
<div id="product_images_container">
    <ul class="product_images">
        <?php
        $attachments         = get_attached_media('image', $post->ID);
        $update_meta         = false;
        $updated_gallery_ids = array();

        if (!empty($attachments)) {
            foreach ($attachments as $attachmentPost) {
                $attachment = wp_get_attachment_image($attachmentPost->ID);

                // if attachment is empty skip.
                if (empty($attachment)) {
                    $update_meta = true;
                    continue;
                }

                echo '<li class="image" data-attachment_id="' . esc_attr($attachmentPost->ID) . '">' . $attachment . '</li>';
            }
        }
        ?>
    </ul>
</div>