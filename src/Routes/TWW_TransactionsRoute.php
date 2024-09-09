<?php
namespace TWWForms\Routes;

class TWW_TransactionsRoute extends TWW_Routes {
    const CREATE_TXN = '/wp-json/mp/v1/transactions';

    protected $routes = [
        'create-transaction' => [
            'methods' => 'POST',
            'callback' => 'create_transaction',
            'path' => '/create-transaction',
            'permission_callback' => '__return_true',
        ],
    ];

    public function boot() {
        $this->register_routes();
    }

    public function create_transaction(\WP_REST_Request $request) {
        $params = $request->get_params();
        $subscription_id = $params['subscription_id'] ?? null;
        $user_id = $params['user_id'] ?? null;
        $membership_id = $params['membership_id'] ?? null;
    
        if (!$subscription_id || !$user_id || !$membership_id) {
            return new \WP_Error(
                'missing_params',
                'Missing required parameters.',
                ['status' => 400]
            );
        }
    
        $membership = new \MeprProduct($membership_id);
        $price = $membership->price;

        $subscription = new \MeprSubscription($subscription_id);
        $gateway = $subscription->gateway;
    
        $created_at_timestamp = time();
        $created_at = date('Y-m-d H:i:s', $created_at_timestamp);
        $expires_at_timestamp = $membership->get_expires_at($created_at_timestamp);
        $expires_at = date('Y-m-d H:i:s', $expires_at_timestamp);
    
        $api_key = get_option('mpdt_api_key', '');
    
        $mp_endpoint_ta = 'wp-json/mp/v1/transactions';
        $url = trailingslashit($this->get_site_url()) . $mp_endpoint_ta;
    
        $data = [
            'subscription' => $subscription_id,
            'member' => $user_id,
            'membership' => $membership_id,
            'total' => $price,
            'created_at' => $created_at,
            'expires_at' => $expires_at,
            'gateway' => $gateway,
            'send_welcome_email' => false
        ];
    
        $request_args = [
            'body' => wp_json_encode($data),
            'headers' => [
                'Content-Type' => 'application/json',
                'MEMBERPRESS-API-KEY' => $api_key,
            ]
        ];
    
        $response = wp_remote_post($url, $request_args);
        $response_body = wp_remote_retrieve_body($response);
    
        $decoded_response = json_decode($response_body, true);
    
        if (is_wp_error($response)) {
            return new \WP_Error(
                'api_error',
                $response->get_error_message(),
                ['status' => 500]
            );
        }

        $txn_id = $decoded_response['id'];

        return rest_ensure_response([
            'success' => true,
            'message' => 'A transaction has been created.',
            'transaction_id' => $txn_id,
            'mp' => $decoded_response
        ]);
    }
    
    function get_expires_at($created_at_timestamp, $membership) {
        return $membership->get_expires_at($created_at_timestamp);
    }    
}