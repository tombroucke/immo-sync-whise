<?php

namespace ADB\ImmoSyncWhise\Admin\CPT;

use ADB\ImmoSyncWhise\Enum\PostType;

class Estate
{
    public function __construct()
    {
        add_action('init', [$this, 'create']);
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('add_meta_boxes_' . PostType::Name, [$this, 'estate_add_meta_boxes']);
        add_filter('manage_posts_columns', [$this, 'featured_image_column']);
        add_action('manage_posts_custom_column', [$this, 'featured_image_column_data'], 10, 2);
        add_action('admin_head', [$this, 'columns_css']);
    }

    public function create()
    {
        $labels = array(
            'name'          => 'Estates', // Plural name
            'singular_name' => 'Estate'   // Singular name
        );

        $supports = array(
            'title',        // Post title
            'thumbnail',    // Allows feature images
            'revisions',    // Shows autosaved version of the posts
        );

        $capabilities = array(
            'create_posts' => false // Disable ability to create new post
        );

        $args = array(
            'labels'              => $labels,
            'description'         => 'Whise Estates', // Description
            'supports'            => $supports,
            'taxonomies'          => array(), // Allowed taxonomies
            'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
            'public'              => true,  // Makes the post type public
            'show_ui'             => true,  // Displays an interface for this post type
            'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
            'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
            'show_in_admin_bar'   => true,  // Displays in the black admin bar
            'menu_position'       => 5,     // The position number in the left menu
            'menu_icon'           => 'dashicons-admin-multisite',  // The URL for the icon used for this post type
            'can_export'          => true,  // Allows content export using Tools -> Export
            'has_archive'         => true,  // Enables post type archive (by month, date, or year)
            'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
            'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
            'capability_type'     => 'post', // Allows read, edit, delete like “Post”
            'capabilities'        => $capabilities,
            'map_meta_cap'        => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );

        register_post_type(PostType::Name, $args); //Create a post type with the slug is ‘product’ and arguments in $args.
    }

    public function estate_add_meta_boxes($post)
    {
        add_meta_box(
            'estate_information_meta_box',
            __('Estate Information', 'wp_whise'),
            [
                $this,
                'information_build_meta_box'
            ],
            PostType::Name,
            'advanced',
            'low'
        );

        add_meta_box(
            'estate_details_meta_box',
            __('Estate Details', 'wp_whise'),
            [
                $this,
                'detail_build_meta_box'
            ],
            PostType::Name,
            'advanced',
            'low'
        );

        add_meta_box(
            'estate_gallery_meta_box',
            __('Estate Gallery', 'wp_whise'),
            [
                $this,
                'gallery_build_meta_box'
            ],
            PostType::Name,
            'advanced',
            'low'
        );
    }

    public function featured_image_column($columns)
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'featured_image' => 'Image',
            'title' => 'Title',
            'date' => 'Date'
        );

        return $columns;
    }

    public function featured_image_column_data($column, $post_id)
    {
        switch ($column) {
            case 'featured_image':
                the_post_thumbnail('thumbnail');
                break;
        }
    }

    public function columns_css()
    {
        echo '<style type="text/css">';
        echo '.column-featured_image { width: 160px !important; overflow:hidden }';
        echo '.column-featured_image img { width: 100px !important; height: 100px !important; }';
        echo '.column-date { width: 30% !important }';
        echo '</style>';
    }

    public function detail_build_meta_box($post)
    {
        include_once ISW_PATH . '/templates/estate/detail.php';
    }

    public function information_build_meta_box($post)
    {
        include_once ISW_PATH . '/templates/estate/information.php';
    }

    public function gallery_build_meta_box($post)
    {
        include_once ISW_PATH . '/templates/estate/gallery.php';
    }

    public function admin_scripts()
    {
        global $post_type;

        if (PostType::Name == $post_type) {
            wp_enqueue_media();
            wp_enqueue_style('whise-jquery-css', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
            // wp_enqueue_script('whise-gallery-js', plugins_url('/immo-sync-whise/assets/admin/js/gallery.js'), ['media-models'], ISW_VERSION);
            // wp_enqueue_script('whise-document-js', plugins_url('/immo-sync-whise/assets/admin/js/document.js'), ['media-models'], ISW_VERSION);
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('whise-index-js', plugins_url('/immo-sync-whise/assets/admin/js/index.js'), ['jquery', 'jquery-ui-accordion'], ISW_VERSION);
            wp_enqueue_style('whise-estate-css', plugins_url('/immo-sync-whise/assets/admin/css/estate.css'), [], ISW_VERSION . time());
        }
    }
}
