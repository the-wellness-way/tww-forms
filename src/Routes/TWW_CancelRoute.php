<?php
namespace TWWForms\Routes;

use TWWForms\Routes\TWW_Routes;

class TWW_CancelRoute extends TWW_Routes {
    protected $routes = [
      'cancel-subscription' => [
          'methods' => 'POST',
          'callback' => 'cancel_subscription',
          'path' => '/cancel-subscription',
          'mp_path' => '/subscriptions/{id}/cancel',
      ]
    ];

    public function boot() {
      $this->register_routes();
    }

    public function cancel_subscription(\WP_REST_Request $request) {
      $params = $request->get_params();

      $subscription_id = $params['active_subscription_id'] && intval($params['active_subscription_id']) ? intval($params['active_subscription_id']) : null;

      if(!$subscription_id || !is_int($subscription_id)) {
        return new \WP_Error('missing_id', 'Missing subscription ID', ['status' => 400]);
      }

      $url = $this->get_mp_endpoint('cancel-subscription', $subscription_id);

      // Redundant, but no reason to send a post request if it's not active.
      $subscription = new \MeprSubscription($params['active_subscription_id']);
      if($subscription->status != 'active') {
        return new \WP_Error('subscription_not_active', 'The subscription must have a status of \'active\' before it can be cancelled.', ['status' => 400]);
      }
    
      try {
        $result = $this->tww_mp_remote_post($url);
      } catch(\Exception $e) {
        return new \WP_Error('wp_error', $e->getMessage(), ['status' => 500]);
      }

      $mp_response = json_decode(wp_remote_retrieve_body($result), true);

      if(isset($mp_response['code'])) {
        return new \WP_Error($mp_response['code'], $mp_response['message'], ['status' => 500]);
      }
    
      if(is_wp_error($result)) {
        return rest_ensure_response([
          'success' => false,
          'error' => new \WP_Error('wp_error', $result->get_error_message, ['status' => 500]),
          'message' => "There has been an error. Please try again later or contact support.",
        ]);
      }
    
      return rest_ensure_response([
        'success' => true,
        'message' => $mp_response['message'],
        'code' => $mp_response['code'],
      ]);
    }
}