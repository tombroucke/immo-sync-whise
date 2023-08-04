<?php

namespace ADB\ImmoSyncWhise\Database;

use Illuminate\Support\Collection;

class IwsDetailsTable
{
    private $table;

    private $wpdb;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->table = $wpdb->prefix . "iws_details";
    }

    public function get($postId)
    {
        $query = $this->wpdb->prepare("SELECT * FROM $this->table WHERE post_id = %s", $postId);

        return Collection::make($this->wpdb->get_results($query));
    }

    public function save($postId, $details)
    {
        return $this->wpdb->insert(
            $this->table,
            [
                'post_id' => $postId,
                'detail_label' => $details['label'],
                'detail_type' => $details['type'],
                'detail_value' => $details['value'],
                'detail_group' => $details['group'],
            ]
        );
    }

    public function update($postId, $details)
    {
        return $this->wpdb->update(
            $this->table,
            [
                'post_id' => $postId,
                'detail_label' => $details['label'],
                'detail_type' => $details['type'],
                'detail_value' => $details['value'],
                'detail_group' => $details['group'],
            ],
            ['post_id' => $postId]
        );
    }

    public function delete($postId)
    {
        return $this->wpdb->delete($this->table, ['post_id' => $postId]);
    }
}
