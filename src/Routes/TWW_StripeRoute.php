<?php
namespace TWWForms\Routes;
use TWWForms\Shortcodes\TWW_MembershipShortcode;

class TWW_StripeRoute extends TWW_Routes {
    const ALLOWED_RESUME_SCENARIOS = [
        'canceled-but-active',
        'canceled-and-expired',
        'lapsed',
        'expired'
    ];

    private $subscription_id = null;

    /**
     * @see https://docs.stripe.com/api/subscriptions/resume#resume_subscription-billing_cycle_anchor
     * @var string
     */
     private static $billing_cycle_anchor_now = 'now'; 
     private static $billing_cycle_anchor_unchanged = 'unchanged';

    protected $routes = [
        'create-stripe-subscription' => [
            'methods' => 'POST',
            'callback' => 'create_stripe_subscription',
            'path' => '/create-stripe-subscription',
            'permission_callback' => '__return_true',
        ]
    ];

    public function boot() {
        $this->register_routes();
    }

    /**
     * 
     * 
     * Note that we want to retry with an Idempotency key in V4 UUIDs format
     * @return void
     */
    public function create_stripe_subcription(\WP_REST_Request $request) {
        $params             = $request->get_params();
        $this->subscription_id    = $params['subscription_id'] && intval($params['subscription_id']) ? intval($params['subscription_id']) : null;
        $customer_id            = $params['user_id'] && intval($params['user_id']) ? $params['user_id'] : null;
        $membership_id      = $params['membership_id'] && intval($params['membership_id']) ? intval($params['membership_id']) : null;
    
        if (!intval($subscription_id) || !intval($customer_id) || !intval($membership_id)) {
            return new \WP_Error(
                'missing_params',
                'Missing required parameters.',
                ['status' => 400]
            );
        }

        $sub        = $this->get_subscription($subscription_id);
        $subscr_id  = $this->get_subscr_id($sub);
        $twwMembershipSC = new TWW_MembershipShortcode(null, $sub, $sub->latest_txn());
        $scenario = $twwMembershipSC->get_scenario();

        if(in_array($scenario, self::ALLOWED_RESUME_SCENARIOS)) {
            $billing_cycle_anchor = $this->get_billing_cycle_anchor($scenario);

            $data = [
                'id' => $subscr_id,
                'billing_cycle_anchor' => $billing_cycle_anchor
            ];

            $meta_data = [
                'ip_address' => '',
                'memberpress_product' =>
                'memberpress_product_id' =>
                'platform' => 'TWW Resume Subcsriptions via Memberpress',
                'site_url' => site_url()
                'transaction_id' => ''
            ];
        }
        
    }

    public function transaction_id($customer_id, $sub) {
        
    }

    public function find_prorate() {

    }

    public function calculate_billing_cycle_anchor() {
        strtotime($this->sub->expires_at) - now();
    }

    public function create_stripe_subscription($customer_id) {


        $data = [
            'customer' => $customer_id,
            'cancel_at_period_end' => false,
            'currency' => 'USD',
            // 'default_payment_method' => '' We want this to already be in Stripe in invoice_settings.default_payment_method
            'items' => [
                "discounts" => [
                    'coupon' => ''
                ],
                "items.discounts.coupon"
            ]
        ]
    }

    public function get_subscription(int $subscription_id = null) {
        if($subscription_id) {
            $subscription = new \MeprSubscription($subscription_id);

            if($subscription->id && 0 !== $subscription->id) {
                return $subscription;
            } else {
                $this->error_log("Subscription with" . $subscription_id . "does not exist");
            }
        } else {
            $this->error_log("Missing Subscription ID.");
        }   

        return null;
    }

    public function proration_behavior() {

    }

    public function get_billing_cycle_anchor($scenario) {
        if (TWW_MembershipShortcode::$expired_str == $scenario) {
            return self::$billing_cycle_anchor_now;
        }
        
        if (TWW_MembershipShortcode::$active_str === $scenario) {
            return self::$billing_cycle_anchor_unchanged;
        }
        
        if (TWW_MembershipShortcode::$canceled_but_active_str === $scenario) {
            return self::$billing_cycle_anchor_unchanged;
        }
        
        if (TWW_MembershipShortcode::$canceled_and_expired_str === $scenario) {
            return self::$billing_cycle_anchor_now;
        }
        
        if (TWW_MembershipShortcode::$lapsed_str === $scenario) {
            return self::$billing_cycle_anchor_now;
        }
        
        if (TWW_MembershipShortcode::$suspended_str === $scenario) {
            return self::$billing_cycle_anchor_unchanged;
        }
        
        if (TWW_MembershipShortcode::$no_subscription_str === $scenario) {
            return self::$billing_cycle_anchor_now;
        }
    }

    public function get_customer() {

    }

    public function get_subscr_id(\MeprSubscription $sub) {
        return $sub->subscr_id ?? null;
    }

    public function error_log(string $message = null) {
        if($message) {
            error_log($message);
        }
    }
}