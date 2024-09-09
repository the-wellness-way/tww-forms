<?php
namespace TWWForms\Routes;

class TWW_SubscriptionRoute extends TWW_Routes {
    const MEMBERS = '/wp-json/mp/v1/members';
    const FREE_SUBSCRIPTION_POST_TITLE = 'Free Subscription';

    protected $routes = [
        'create-member' => [
            'methods' => 'POST',
            'callback' => 'create_member',
            'path' => '/create-member',
            'permission_callback' => '__return_true',
        ],
        'update-user' => [
            'methods' => 'POST',
            'callback' => 'update_user',
            'path' => '/update-user',
            'permission_callback' => '__return_true'
        ],
        'update-subscription' => [
            'methods' => 'POST',
            'callback' => 'update_subscription',
            'path' => '/update-subscription',
            'permission_callback' => '__return_true'
        ]
    ];

    public function boot() {
        $this->register_routes();
    }

    public function get_free_membership() {
        $post_title = self::FREE_SUBSCRIPTION_POST_TITLE;

        $membership = new \WP_Query([
            'post_type' => 'memberpressproduct',
            'posts_per_page' => 1,
            'title' => $post_title
        ]);

        return $membership->posts[0]->ID ?? null;
    }

    public function create_member(\WP_REST_Request $request) {
        $params = $request->get_params();
        $post_id = $params['post_id'] ?? null;

        if(!$params['email'] || !$params['username'] || !is_email($params['email'])) {
            return new \WP_Error('missing_params', 'Missing required parameters', ['status' => 400]);
        }

        if(!$post_id || 'memberpressproduct' === get_post_type($post_id)) {
            $membership_id = $this->get_free_membership();
        } 

        $api_key = get_option('mpdt_api_key', '');

        if(!$api_key) {
            return new \WP_Error('api_key_missing', 'API key is missing', ['status' => 400]);
        }

        $url = $this->get_site_url() . self::MEMBERS;

        $response = wp_remote_post($url, [
            'body' => json_encode([
                'email' => $params['email'],
                'username' => $params['email'],
                'first_name' => '',
                'last_name' => '',
                'send_welcome_email' => false,
                'transaction' => [
                    'membership' => $membership_id,
                    'amount' => '0.00',
                    'total' => '0.00',
                    'tax_amount' => '0.00',
                    'tax_rate' => '0.000',  
                    'trans_num'   => 'mp-txn-' . uniqid(),
                    'status'      => 'complete',
                    'gateway'     => 'free',
                    'created_at'  => gmdate( 'c' ),
                    'expires_at'  => '0000-00-00 00:00:00'
                ]
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
                'MEMBERPRESS-API-KEY' => $api_key,
            ]
        ]);

        if(is_wp_error($response)) {
            return new \WP_Error('api_error', $response->get_error_message(), ['status' => 500]);
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        if($response_body && array_key_exists('code', $response_body) && 'mp_db_create_error' === $response_body['code']) {
            return new \WP_Error('member_exists', 'Error creating member. You may have already subscribed.', ['status' => 400]);
        }

        if($response_body && array_key_exists('id', $response_body) && !current_user_can('manage_options')) {
            $user = new \MeprUser($response_body['id']);
            $wp_user = get_user_by('email', $params['email']);
            
            if($user->ID) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $params['email'], $wp_user);

                $user_login = $wp_user->user_login;
                
                $user->send_password_notification('reset');
            }
        }

        $data = [
            'status' => 'success',
            'message' => 'Member created successfully',
            'redirect' => $params['redirect_url'] ?? '',
            'data' => $response_body
        ];

        return rest_ensure_response($data);
    }

    public function update_user(\WP_REST_Request $request) {
        $params = $request->get_params();
        $user_id = $params['user_id'] ?? get_current_user_id();
        $first_name = $params['first_name'] ?? '';
        $last_name = $params['last_name'] ?? '';
        $email = $params['email'] ?? null;

        if(!$user_id) {
            return new \WP_Error('missing_user_id', 'User ID is missing', ['status' => 400]);
        }

        if(null !== $first_name) {
            $updated_first_name = wp_update_user([
                'ID' => $user_id,
                'first_name' => $first_name
            ]);
        }

        if(null !== $last_name) {
            $updated_last_name = wp_update_user([
                'ID' => $user_id,
                'last_name' => $last_name
            ]);
        }

        if(null === $email || false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new \WP_Error('invalid_email', 'Invalid email address', ['status' => 400]);
        }

        $updated_email = wp_update_user([
            'ID' => $user_id,
            'user_email' => $email
        ]);

        $data = [
            "success" => true,
            "message" => "User updated successfully",
            "data" => [
                "user_id" => $user_id,
                "first_name" => $params['first_name'] ?? $updated_first_name,
                "last_name" => $params['last_name'] ?? $updated_last_name,
                "email" => $params['email'] ?? $updated_email,
            ]
        ];

        return new \WP_REST_Response($data);
    }

    public function update_subscription(\WP_REST_Request $request) {
        $params = $request->get_params();
        $user_email = $params['user_email'] ?? null;
        $user_id = $params['user_id'] ?? null;
        $subscription_id = $params['subscription_id'] ?? null;
        $membership_id = $params['membership_id'] ?? null;
        $status = $params['status'] ?? null;
    
        $api_key = get_option('mpdt_api_key', '');

        if(!$params['user_id'] && $user_email)  {
            $user = get_user_by('email', $user_email);
            $user_id = $user->ID;
        }
    
        if (!$user_id || !$subscription_id || !$membership_id || !$status) {
            return rest_ensure_response(['error' => 'Missing required parameters'], 400);
        }
    
        $subscription = new \MeprSubscription($subscription_id);
        $created_at = $subscription->created_at;
        $subscr_id = $subscription->subscr_id;
    
        $mp_endpoint = 'wp-json/mp/v1/subscriptions/' . $subscription_id;
        $url = trailingslashit($this->get_site_url()) . $mp_endpoint;
    
        $data = [
            'subscr_id' => $subscr_id,
            'member' => $user_id,
            'membership' => $membership_id,
            'status' => $status,
            'created_at' => $created_at
        ];
    
        $request_args = [
            'body' => wp_json_encode($data),
            'headers' => [
                'Content-Type' => 'application/json',
                'MEMBERPRESS-API-KEY' => $api_key,
            ]
        ];
    
        $response = wp_remote_post($url, $request_args);
    
        if (is_wp_error($response)) {
            return rest_ensure_response(['error' => 'Request failed', 'details' => $response->get_error_message()], 500);
        }
    
        $response_body = wp_remote_retrieve_body($response);
        $decoded_response = json_decode($response_body, true);

        $response = [
            'success' => true,
            'message' => 'Your membership has been updated. Please wait while the page reloads.',
            'mp' => $decoded_response
        ];
    
        return rest_ensure_response($response);
    }
}