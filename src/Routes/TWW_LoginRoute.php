<?php
namespace TWWForms\Routes;

use TWWForms\Routes\TWW_Routes;

class TWW_LoginRoute extends TWW_Routes {
    protected $routes = [
      'login' => [
          'methods' => 'POST',
          'callback' => 'login',
          'path' => '/login',
      ]
    ];

    public function boot() {
      $this->register_routes();
    }

    public function login(\WP_REST_Request $request) {
      $params = $request->get_params();
      $use_auth_0 = true === $params['use_auth_0'] || 'true' === $params['use_auth_0'] ? true : false;

      if(!isset($params['email']) || !isset($params['password'])) {
        return new \WP_Error('missing_params', 'Missing email or password', ['status' => 400]);
      }

      if(true === $use_auth_0 && class_exists('TwwFormsAuth0\Includes\TwwfAuth0Login')) {
        $auth0_login = new \TwwFormsAuth0\Includes\TwwfAuth0Login();
        $auth0_response = $auth0_login->twwf_auth0_login($params['email'], $params['password']);

        return $auth0_response;
      }    


        $user = wp_signon([
            'user_login' => $params['email'],
            'user_password' => $params['password'],
            'remember' => true
        ]);

        if(is_wp_error($user)) {
            return new \WP_Error('login_error', 'Invalid email or password', ['status' => 400]);
        }

        return rest_ensure_response([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user
        ]);
    }
}