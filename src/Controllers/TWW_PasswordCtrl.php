<?php
namespace TWWForms\Controllers;
/**
 * This class will:
 * - Add an option in wp_user_meta table to log the number of times a user has reset their password with date and time 
 */
class TWW_PasswordCtrl {
    const PREFIX = 'tww__';
    const META_KEY = 'pwd_resets';

    public function __construct() {
        add_action('password_reset', [$this, 'add_pwd_reset_entry'], 10, 2);

        // We prefer to do this via memberpress hooks rather than WP hooks
        add_action('mepr_extra_profile_fields', [$this, 'render_editable_custom_fields']);
    }

    public function add_pwd_reset_entry($user, $new_pass) {
        $user_id = $user->ID;
        $meta_key = self::PREFIX . self::META_KEY;
        $meta_value = get_user_meta($user_id, $meta_key, true);

        if(!$meta_value) {
            $meta_value = [];
        }

        $meta_value[] = [
            'date' => date('Y-m-d H:i:s'),
        ];

        update_user_meta($user_id, $meta_key, $meta_value);
    }

    public function render_editable_custom_fields($user) {
        $pwd_resets = $this->get_pwd_resets($user->ID);
        if ($pwd_resets && is_array($pwd_resets)) {
            echo '<tr>';
            echo '<th>Password Resets</th>';
            echo '<td>';
            echo '<ul>';
            foreach($pwd_resets as $entry) {
                echo '<li>' . $entry['date'] . '</li>';
            }
            echo '</ul>';
            echo '</td>';
            echo '</tr>';
        }
    }

    public function get_pwd_resets($user_id) {
        $meta_key = self::PREFIX . self::META_KEY;
        $meta_value = get_user_meta($user_id, $meta_key, true);

        if(!$meta_value) {
            $meta_value = [];
        }

        return $meta_value;
    }
}