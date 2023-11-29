<?php

namespace ADB\ImmoSyncWhise\Frontend;

use ADB\ImmoSyncWhise\Api;
use ADB\ImmoSyncWhise\Vendor\Illuminate\Container\Container;

class Contacts
{

    public function __construct(private Container $container)
    {
        add_action('admin_post_add_whise_contact', [$this, 'add']);
        add_action('admin_post_nopriv_add_whise_contact', [$this, 'add']);
        add_shortcode('whise_contact_form', [$this, 'renderAddContactForm']);
    }

    public function add() : void
    {
        $referer = strtok(wp_get_referer(), '?') . '#whise-contact-form';
        if (!wp_verify_nonce($_POST['whise_create_contact_nonce'], 'whise_create_contact')) {
            wp_safe_redirect(add_query_arg(['whise_contact_error' => 'nonce'], $referer));
            die();
        }

        $data = apply_filters('iws_contact_form_data', [
            'firstName' => sanitize_text_field($_POST['first_name']),
            'lastName' => sanitize_text_field($_POST['last_name']),
            'Name' => sanitize_text_field($_POST['first_name']) . ' ' . sanitize_text_field($_POST['last_name']),
            'PrivateEmail' => sanitize_email($_POST['email']),
            'EstateIds' => [filter_input(INPUT_POST, 'whise_estate_id', FILTER_SANITIZE_NUMBER_INT)],
            'LanguageId' => 'nl-BE',
            'CountryId' => 1,
            'StatusId' => 1,
            'OfficeIds' => [filter_input(INPUT_POST, 'office_id', FILTER_SANITIZE_NUMBER_INT)]
        ]);

        try {
            $contact = $this->container->make(Api::class)->connection->contacts()->upsert($data);
        } catch (\Throwable $th) {
            wp_redirect(add_query_arg(['whise_contact_error' => 'api'], $referer));
            exit;
        }
        
        wp_redirect(add_query_arg(['whise_contact_upserted' => 'yes'], $referer));
        exit;
    }

    public function renderAddContactForm($atts = []) : string
    {
        $atts = shortcode_atts(array(
            'estateId' => get_the_ID(),
        ), $atts, 'whise_contact_form');
    
        $estateId = $atts['estateId'];
        $whiseEstateId = get_post_meta($estateId, '_iws_id', true);
        $officeId = get_post_meta($estateId, '_iws_officeId', true);
       
        $data = [
            'adminPostUrl' => admin_url('admin-post.php'),
            'officeId' => $officeId,
            'estateId' => $estateId,
            'whiseEstateId' => $whiseEstateId,
            'notices' => $this->notices(),
        ];

        return apply_filters(
            'iws_contact_form',
            $this->container->render('estate/contact/form', $data),
            $data
        );
    }

    private function notice($type, $message) : array
    {
        return [
            'type' => $type,
            'message' => $message,
        ];
    }

    private function notices() : array
    {
        $notices = [];
        if (isset($_GET['whise_contact_error'])) {
            switch ($_GET['whise_contact_error']) {
                case 'nonce':
                    $notices[] = $this->notice('error', __('Security check failed, please try again.', 'immo-sync-whise'));
                    break;
                case 'api':
                    $notices[] = $this->notice('error', __('Something went wrong, please try again.', 'immo-sync-whise'));
                    break;
            }
        }

        if (isset($_GET['whise_contact_upserted']) && $_GET['whise_contact_upserted'] == 'yes') {
            $notices[] = $this->notice('success', __('Your contact request has been sent.', 'immo-sync-whise'));
        } elseif (isset($_GET['whise_contact_upserted'])) {
            $notices[] = $this->notice('error', __('Something went wrong, please try again.', 'immo-sync-whise'));
        }

        return $notices;
    }
}
