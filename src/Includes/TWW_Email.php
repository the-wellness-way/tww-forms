<?php

namespace TWWForms\Includes;

class TWW_Email {
    public function __construct() {
        add_filter('retrieve_password_message', [$this, 'send_custom_retrieve_password_change_email'], 10, 4);
        add_filter('wp_mail_content_type', [$this, 'set_html_content_type']);
    }

    public function send_custom_retrieve_password_change_email($message, $key, $user_login, $user_data) {
        $reset_key = $key;
        $user = get_user_by('login', $user_login);
        $user_display_name = isset($user->display_name) ? $user->display_name : $user_login;
        $template_path = plugin_dir_path(__FILE__) . 'templates/emails/user_reset_password.php';
        $reset_link = $this->generate_reset_password_link($user_login);
    
        $email = null;
        if (filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
            $email = $user_login;
        } else {
            $email = $user->user_email;
        }
    
        $email_vars = array(
            'name' => $user_display_name,
            'user_login' => $user_login,
            'reset_link' => $reset_link,
            'reset_key' => $reset_key,
            'welcome_string' => ''
        );
    
        $email_content = $this->get_template_content($template_path, $email_vars);
    
        return $email_content;
    }

    /**
     * Genereate the password reset link
     *
     * @param $user_login
     * @return false|string
     */
    function generate_reset_password_link($user_login) {
        $user = get_user_by('login', $user_login);
        if (class_exists('MeprUser') && method_exists('MeprUser', 'reset_password_link')) {
            $user = new MeprUser($user->ID);
            
            return $user->reset_password_link();
        }

        $key = get_password_reset_key($user);

        if (is_wp_error($key)) {
            return false;
        }

        $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');

        return $reset_url;
    }

    function get_template_content($template_path, $vars = array()) {
        if(file_exists($template_path)) {
            extract($vars);

            ob_start();

            include $template_path;

            return ob_get_clean();
        }

        return false;
    }

    function set_html_content_type() {
        return 'text/html';
    }
}