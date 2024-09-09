<?php
namespace TWWForms\Shortcodes;

class TWW_ChangePasswordShortcode extends TWW_Shortcodes {
    public function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_change_password',
            'handle' => 'tww-change-password-form-shortcode',
        ];
    }

    public function render_shortcode($atts, $content = null) {
        //wp_enqueue_script($this->sc_settings['handle']);

        //if current user isn't logged in return a prompt to log in

        if(!is_user_logged_in()) {
            return '<p>Please log in to change your password</p>';
        }

        ob_start();
        include TWW_FORMS_PLUGIN . 'templates/'.$this->sc_settings['handle'].'.php';
        return ob_get_clean();
    }
}