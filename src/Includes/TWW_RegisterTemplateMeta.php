<?php
namespace TwwForms\Includes;

class TWW_RegisterTemplateMeta {
    public function __construct() {
        add_action('init', [$this, 'register_membership_id_field']);
        add_action('save_post', [$this, 'save_membership_id_meta_box']);
    }

    public function register_membership_id_field() {
        add_action('add_meta_boxes', [$this, 'check_page_template_and_register_meta_box']);
    }

    public function check_page_template_and_register_meta_box() {
        global $post;

        if (!$post) return;

        $page_template = get_post_meta($post->ID, '_wp_page_template', true);

        if ('template-register.php' === $page_template) {
            register_post_meta('post', 'membership_id', [
                'show_in_rest' => true,  
                'single' => true,        
                'type' => 'string',     
                'sanitize_callback' => 'sanitize_text_field', 
                'auth_callback' => '__return_true'
            ]);

            add_meta_box('membership_id_meta_box', 'Membership ID', [$this, 'display_membership_id_meta_box'], 'page', 'normal', 'high');
        }
    }

    public function display_membership_id_meta_box($post) {
        $membership_id = get_post_meta($post->ID, 'membership_id', true);
        echo '<label for="membership_id_field">Membership ID:</label>';
        echo '<input type="text" id="membership_id_field" name="membership_id_field" value="' . esc_attr($membership_id) . '" />';
    }

    public function save_membership_id_meta_box($post_id) {
        if (array_key_exists('membership_id_field', $_POST)) {
            update_post_meta($post_id, 'membership_id', sanitize_text_field($_POST['membership_id_field']));
        }
    }
}
