<?php
namespace TWWForms\Shortcodes;

class TWW_MembershipShortcode extends TWW_Shortcodes {
    const SHORTCODE_NAME = 'TWW+ Membership';
    const NO_ACTIVE_TITLE = 'No active membership';

    /**
     * 
     * Mepr objects
     */
    private $product = null;
    private $subscription = null;
    private $transaction = null;
    private $user = null;

    /**
     * 
     * 
     * Scenarios
     */
    private static $canceled_but_active_str = 'canceled-but-active';
    private static $canceled_and_expired_str = 'canceled-and-expired';
    private static $active_str = 'active';
    private static $lapsed_str = 'lapsed';
    private static $expired_str = 'expired';
    private static $suspended_str = 'suspended';
    private static $no_subscription_str = 'no-subscription';
    // MemberPress spells "cancelled" two different ways within their plugin (with 'l' and with 'll' at the end of the word)
    private static $mp_canceled_str = 'cancelled';

    private $scenario;

    protected function set_sc_settings() {
        $this->sc_settings = [
            'name' => 'tww_current_membership',
            'handle' => 'tww-current-membership-shortcode',
            'capability' => 'edit_user',
            'permission_callback' => 'is_user_logged_in',
        ];
    }

    public function __construct(\MeprProduct $product = null, \MeprSubscription $subscription, \MeprTransaction $transaction = null) {
        parent::__construct();
        
        $this->product = $product ? $product : $this->subscription_product();
        $this->subscription = $subscription ?? null;
        $this->transaction = $transaction ? $transaction : $this->subscription_latest_txn();
        $this->user = new \MeprUser(get_current_user_id());
    }

    public function render_shortcode($atts, $content = null) {
        // if($this->sc_settings['handle']) {
        //     wp_enqueue_script($this->sc_settings['handle']);
        // }

        if(!$this->user->ID) {
            return '<p>You must be logged in to view this content</p>';
        }

        /**
         * Variables are used in the template file
         * 
         * 
         * 
         */
        $user_id = $this->user->ID;
        $subscription = $this->subscription;
        $prd = $this->product;
    
        ob_start();
        include TWW_FORMS_PLUGIN . 'templates/current-membership-shortcode.php';
        return ob_get_clean();
    }

    public function get_scenario() {
        $status = $this->subscripiton_status();
        $txn = $this->subscription_latest_txn();
        $latest_txn_failed = $this->subscription_latest_txn_failed();
        $is_expired = $this->subscription_is_expired();
        $in_grace_period = $this->subscription_in_grace_period();
        $scenario = '';

        if (!$in_grace_period && $is_expired) {
            $scenario = self::$expired_str;
        }
        
        if (self::$active_str === $status && !$latest_txn_failed && !$is_expired) {
            $scenario = self::$active_str;
        }
        
        if (self::$mp_canceled_str === $status && !$is_expired) {
            $scenario = self::$canceled_but_active_str;
        }
        
        if (self::$mp_canceled_str === $status && $is_expired) {
            $scenario = self::$canceled_and_expired_str;
        }
        
        if (self::$active_str === $status && !$in_grace_period && ($latest_txn_failed || false !== strpos($status, 'No'))) {
            $scenario = self::$lapsed_str;
        }
        
        if (!$txn && !$in_grace_period) {
            $scenario = self::$lapsed_str;
        }
        
        if (self::$suspended_str === $status) {
            $scenario = self::$suspended_str;
        }
        
        if (!$this->subscription->id) {
            $scenario = self::$no_subscription_str;
        }
        
        return $scenario;
    }

    /**
     * 
     * Printing functions
     * 
     * 
     * 
     */

     public function print_status_tag() {
        $scenario = $this->get_scenario();

        if (strpos($scenario, 'canceled') !== false) {
            $string = 'Canceled';
        } elseif (self::$active_str === $scenario) {
            $string = 'Active';
        } elseif (self::$lapsed_str == $scenario) {
            $string = 'Lapsed';
        } elseif (self::$suspended_str === $scenario) {
            $string = 'Paused';
        } elseif (self::$no_subscription_str === $scenario) {
            $string = 'No Subscriptions';
        } elseif (self::$expired_str === $scenario) {
            $string = 'Expired';
        } else {
            $string = '';
        }
    
        return sprintf('<span class="status-tag %s">%s</span>', $scenario, $string);
    }

    public function print_membership_string() {
        $scenario = $this->get_scenario();
        $price = $this->subscription_price() ?? '';
        $next_billing_at = $this->subscription_next_billing_at() ?? '';
        $expires_at = $this->subscription_expires_at() ? \MeprAppHelper::format_date($this->subscription_expires_at()) : '';

        switch ($scenario) {
            case self::$active_str:
                $string = sprintf('<p>Your next bill is for <strong>$%s</strong> on <strong>%s</strong></p>', $price, $next_billing_at);
                break;
            case self::$canceled_but_active_str:
                $string = sprintf('<p>Your membership has been canceled but is still active until <strong>%s</strong></p>', $expires_at);
                break;
            case self::$canceled_and_expired_str:
                $string = sprintf('<p>Your membership has been canceled and expired on <strong>%s</strong></p>', $expires_at);
                break;
            case self::$lapsed_str:
                $string = sprintf('<p>There may have been a problem with your latest payment. Please check with your bank or update your card. If you have any further problems, pleae contact support.</p>');
                break;
            case self::$expired_str:
                $string = sprintf('<p>Your membership has expired on <strong>%s</strong></p>', $expires_at);
                break;
            default:
                $string = '';
                break;
        }        
       
        return $string;
    }

    public function print_renewel_button() {
        $renewal_link = $this->user->renewal_link($this->transaction->id);

        if(!$renewal_link) {
            return '';
        }

        return sprintf(
        '<a id="tww-renew-subscription" href="'.$renewal_link.'" class="loader-default--primary loader-default">
            <span class="loader--inner-element"></span>
            Renew Membership
        </a>');
    }

    public function print_card_plan_buttons() {
        $pm = $this->subscription_pm();
        $prd = $this->product;
        $url = $this->get_update_card_url();

        $html = '';

        if($pm) {
            $html .= sprintf('
            <a id="tww-update-card" href="%s" class="card-plan loader-default--primary loader-default">
                <span class="loader--inner-element"></span>
                Update Card
            </a>', $url);
        }
                    
        if($prd->group()) {
            $html .= sprintf('
            <a id="tww-change-plan-button"  href="#" class="card-plan loader-default--primary loader-default">
                <span class="loader--inner-element"></span>
                Change Plan
            </a>');
        }
        
        return $html;
    }

    public function print_join_button() {
        $url = site_url('/join');

        return sprintf(
        '<a href="%s" class="join loader-default--primary loader-default">
            Join
        </a>', $url);
    }

    public function print_cancellation_button() {
        return sprintf(
        '<a id="tww-cancel-subscription" href="#" class="tww-negative-action">
            <span class="loader--inner-element"></span>
            Cancel Membership
        </a>');
    }

    public function print_actions() {
        $scenario = $this->get_scenario();

        $html_group_cancel_renew = '';
        $html_group_card_plan = '<div class="current-membership--action-group card-plan">';
        if($this->subscription->id) {
            $html_group_cancel_renew = '<div class="current-membership--action-group cancel-renew">';
            if (self::$active_str === $scenario) {
                $html_group_card_plan .= $this->print_card_plan_buttons();
                $html_group_cancel_renew .= $this->print_cancellation_button();
            }
            
            if (self::$canceled_but_active_str === $scenario || self::$canceled_and_expired_str === $scenario) {
                $html_group_card_plan .= $this->print_card_plan_buttons();
                $html_group_card_plan .= $this->print_renewel_button();
            }
            
            if (self::$expired_str === $scenario) {
                $html_group_card_plan .= $this->print_card_plan_buttons();
                $html_group_card_plan .= $this->print_renewel_button();
            }
            
            if (self::$lapsed_str === $scenario || self::$suspended_str === $scenario) {
                $html_group_card_plan .= $this->print_card_plan_buttons();
                $html_group_cancel_renew .= '';
            }
            

            $html_group_cancel_renew .= "</div>";
        } else {
            $html_group_card_plan .= $this->print_join_button();
        }

        $html_group_card_plan .= "</div>";

        $html = $html_group_card_plan . $html_group_cancel_renew;

        return $html;
    }

    /**
     * 
     * Getters (wrappers for memberpress for testing purposes)
     */

    public function subscription_pm() {
        return $this->subscription ? $this->subscription->payment_method() : null;
    }

    public function subscription_expires_at() {
        return $this->subscription ? $this->subscription->expires_at : null;
    }

    public function subscription_next_billing_at() {
        if($this->subscription) {
            $next_billing_at = $this->subscription->next_billing_at;
        }

        return $this->subscription && $next_billing_at ? \MeprAppHelper::format_date($next_billing_at) : null;
    }

    public function subscription_price() {
        return $this->subscription ? $this->subscription->price : null;
    }

    public function subscripiton_status() {
        return $this->subscription ? $this->subscription->status : null;
    }

    public function subscription_is_expired() {
        return $this->subscription ? $this->subscription->is_expired() : null;
    }

    public function subscription_in_grace_period() {
        return $this->subscription ? $this->subscription->in_grace_period() : null;
    }

    public function subscription_product() {
        return $this->subscription ? $this->subscription->product() : null;
    }

    public function subscription_latest_txn_failed() {
        return $this->subscription ? $this->subscription->latest_txn_failed() : null;
    }

    public function subscription_latest_txn() {
        return $this->subscription && $this->subscription->latest_txn() ? $this->subscription->latest_txn() : null;
    }

    public function get_update_card_url() {
        return $this->subscription->id ? site_url('/account/?action=update&sub=' . $this->subscription->id) : null;
    }

    public function print_tag() {
        $scenario = $this->get_scenario();
        return sprintf('<span class="status-tag %s">%s</span>', $scenario, $this->scenario);
    }

    public function print_title($product_title = null) {
        $prd = $this->subscription->product();

        if($prd && $prd->post_title && null === $product_title) {
            $product_title = $prd->post_title;
        }

        $title = $product_title ? $product_title : self::NO_ACTIVE_TITLE;

        return sprintf('<h3>%s</h3>', $title);
    }
}