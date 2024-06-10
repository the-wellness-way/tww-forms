<?php
namespace TWWForms\Routes;

use TWWForms\Routes\TWW_Routes;

class TWW_ChangePasswordRoute extends TWW_Routes {
    protected $routes = [
        'change-password' => [
            'methods' => 'POST',
            'callback' => 'change_password',
            'path' => '/change-password',
            'permission_callback' => '__return_true',
        ],
      ];
  
      public function boot() {
        $this->register_routes();
      }
      public function change_password(\WP_REST_Request $request) {
        $params = $request->get_params();
  
        if(!isset($params['user_id'])) {
          return new \WP_Error('missing_params', 'Missing User ID.', ['status' => 400]);
        }

        if(!isset($params['current_password'])) {
          return new \WP_Error('missing_params', 'Missing password.', ['status' => 400]);
        }

        if(!isset($params['new_password'])) {
          return new \WP_Error('missing_params', 'Missing new password.', ['status' => 400]);
        }

        if(strlen($params['new_password']) < 8) {
          return new \WP_Error('password_too_short', 'Password must be at least 8 characters long.', ['status' => 400]);
        }

        //Password must have one capital letter and one number
        if(!preg_match('/[a-z]/', $params['new_password']) || !preg_match('/[A-Z]/', $params['new_password']) || !preg_match('/[0-9]/', $params['new_password'])) {
          return new \WP_Error('password_invalid', 'Password must have at least one capital letter, one lowercase letter, and one number and be 8 or more characters.', ['status' => 400]);
        }
  
        $user = get_user_by('ID', $params['user_id']);
  
        if(!$user) {
          return new \WP_Error('user_not_found', 'User not found', ['status' => 400]);
        }
  
        if(!wp_check_password($params['current_password'], $user->data->user_pass, $user->ID)) {
          return new \WP_Error('invalid_password', 'Entered invalid current password.', ['status' => 400]);
        }
  
        wp_set_password($params['new_password'], $user->ID);

         // Log the user in programmatically
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
  
        return rest_ensure_response([
          'success' => true,
          'message' => 'Password changed successfully',
        ]);
      }
}