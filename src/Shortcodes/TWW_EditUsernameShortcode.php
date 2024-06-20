<?php
namespace TWWForms\Shortcodes;

class TWW_EditUsernameShortcode extends TWW_Shortcodes {
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_edit_userinfo',
            'handle' => 'tww-edit-username',
            'capability' => 'edit_user',
            'permission_callback' => 'is_user_logged_in',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        $atts = shortcode_atts([
            'justify' => 'flex-start',
        ], $atts);

        // if($this->sc_settings['handle']) {
        //     wp_enqueue_script($this->sc_settings['handle']);
        // }

        if($this->validate_user(wp_get_current_user()) === false) {
            return 'You must be logged in to edit your username';
        }

        $user_id        = get_current_user_id();
        $user_info      = get_userdata($user_id);

        $first_name     = get_user_meta($user_id, 'first_name', true);
        $last_name      = get_user_meta($user_id, 'last_name', true);
        $email          = $user_info->user_email;
        
        ob_start();
        include TWW_FORMS_PLUGIN . 'templates/edit-username-form-shortcode.php';
        return ob_get_clean();
    }
}