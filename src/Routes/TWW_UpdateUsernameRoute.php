<?php
namespace TWWForms\Routes;

class TWW_UpdateUsernameRoute extends TWW_Routes {
    protected $routes = [
        'update_name' => [
            'methods' => 'POST',
            'callback' => 'update_name',
            'path' => '/update-user-name',
        ]
    ];

    public function boot() {
        $this->register_routes();
    }

    public function update_name(\WP_REST_Request $request) {
        $params = $request->get_params();
        $user_id = $params['user_id'] ?? null;
        $first_name = $params['first_name'] ?? null;
        $last_name = $params['last_name'] ?? null;

        if(!$user_id || !$first_name || !$last_name) {
            return new \WP_Error('missing_params', 'Missing required parameters', ['status' => 400]);
        }

        $user = get_user_by('ID', $user_id);

        if(!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        $user->first_name = $first_name;
        $user->last_name = $last_name;

        $update_user = wp_update_user($user);

        return rest_ensure_response([
            'success' => true,
            'message' => 'User name updated successfully',
        ]);
    }
}