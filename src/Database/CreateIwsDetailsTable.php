<?php

namespace ADB\ImmoSyncWhise\Database;

class CreateIwsDetailsTable
{
    public function __construct()
    {
        register_activation_hook(PLUGIN__FILE__, [$this, 'create_wp_iws_details_table']);
    }

    public function create_wp_iws_details_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'iws_details';

        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                post_id bigint(20) unsigned NOT NULL default '0',
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                detail_label varchar(255) NULL,
                detail_type varchar(255) NULL,
                detail_value varchar(255) NULL,
                detail_group varchar(255) NULL,
                UNIQUE KEY id (id),
                FOREIGN KEY (post_id) REFERENCES wp_posts (id) ON DELETE CASCADE
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
