<?php

namespace ADB\ImmoSyncWhise\Model;

use ADB\ImmoSyncWhise\Enum\PostType;
use ADB\ImmoSyncWhise\Model\Model;
use Throwable;

class Estate extends Model
{
    public function get_estate($postId)
    {
        return get_post($postId);
    }

    public function get_estate_exists($estateId)
    {
        return get_posts(array(
            'posts_per_page' => 1,
            'meta_key' => '_iws_id',
            'meta_value' => $estateId,
            'fields' => 'ids', // we don't need it's content, etc.
        ));
    }

    public function save($estate)
    {
        return wp_insert_post([
            'post_title' =>  $estate->name,
            'post_status' => 'publish',
            'post_type' => PostType::Name,
        ]);
    }

    public function update($estate)
    {
        $post = get_posts([
            'meta_query' => [[
                'key' => '_iws_id',
                'value' => $estate->getData()['id']
            ]],
            'post_type' => PostType::Name,
            'posts_per_page' => 1
        ]);

        $updatedPost =  wp_update_post([
            'ID' => end($post)->ID,
            'post_title' =>  $estate->name,
            'post_status' => 'publish',
        ], true);

        return $updatedPost;
    }

    public function save_meta($postId, $estate)
    {
        foreach ($estate->getData() as $key => $value) {
            add_post_meta($postId, '_iws_' . $key, $value, true);
        }
    }

    public function update_meta($postId, $estate)
    {
        foreach ($estate->getData() as $key => $value) {
            update_post_meta($postId, '_iws_' . $key, $value);
        }
    }

    public function getIwsMetaFields($postId)
    {
        $fields = get_post_meta($postId);
        $iwsMeta = [];

        foreach ($fields as $key => $field) {
            if (str_contains($key, 'iws')) $iwsMeta[$key] = $field;
        }

        return $iwsMeta;
    }

    public function exists($whiseEstateId)
    {
        try {
            global $wpdb;

            $sql = $wpdb->prepare('SELECT * FROM `wp_postmeta` WHERE `meta_key` = %s AND `meta_value` = %d', '_iws_id', $whiseEstateId);
            $res = $wpdb->get_results($sql);

            return count($res) > 0;
        } catch (Throwable  $e) {
            $error = json_encode($e->getMessage());

            $this->logger->error("There was an error when verifying if the property already exists {$error}");
        }
    }
}
