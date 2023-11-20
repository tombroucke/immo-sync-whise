<?php

namespace ADB\ImmoSyncWhise\Model;

use ADB\ImmoSyncWhise\Enum\PostType;
use ADB\ImmoSyncWhise\Model\Contracts\ModelContract;
use ADB\ImmoSyncWhise\Vendor\Psr\Log\LoggerInterface;
use Throwable;

class Estate implements ModelContract
{
    public function __construct(public LoggerInterface $logger)
    {
    }

    public function get_estate($postId): \WP_Post|array|null
    {
        return get_post($postId);
    }

    public function get_estate_exists(int $id)
    {
        return get_posts(array(
            'posts_per_page' => 1,
            'meta_key' => '_iws_id',
            'meta_value' => $id,
            'fields' => 'ids',
        ));
    }

    public function save($estate): int|\WP_Error
    {
        return wp_insert_post([
            'post_title' =>  $estate->name,
            'post_status' => 'publish',
            'post_type' => PostType::Name,
        ]);
    }

    public function update($model): int|\WP_Error
    {
        $post = get_posts([
            'meta_query' => [[
                'key' => '_iws_id',
                'value' => $model->getData()['id']
            ]],
            'post_type' => PostType::Name,
            'posts_per_page' => 1
        ]);

        $updatedPost =  wp_update_post([
            'ID' => end($post)->ID,
            'post_title' =>  $model->name,
            'post_status' => 'publish',
        ], true);

        return $updatedPost;
    }

    public function saveMeta($postId, $estate): void
    {
        foreach ($estate->getData() as $key => $value) {
            add_post_meta($postId, '_iws_' . $key, $value, true);
        }
    }

    public function updateMeta($postId, $estate): void
    {
        foreach ($estate->getData() as $key => $value) {
            update_post_meta($postId, '_iws_' . $key, $value);
        }
    }

    public function getMetaFields($postId): array
    {
        $fields = get_post_meta($postId);
        $iwsMeta = [];

        foreach ($fields as $key => $field) {
            if (str_contains($key, 'iws')) $iwsMeta[$key] = $field;
        }

        return $iwsMeta;
    }

    public function exists($providerId): bool
    {
        try {
            global $wpdb;

            $sql = $wpdb->prepare('SELECT * FROM `wp_postmeta` WHERE `meta_key` = %s AND `meta_value` = %d', '_iws_id', $providerId);
            $res = $wpdb->get_results($sql);

            return count($res) > 0;
        } catch (Throwable  $e) {
            $error = json_encode($e->getMessage());

            $this->logger->error("There was an error when verifying if the property already exists {$error}");
        }
    }
}
