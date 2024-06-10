<?php
namespace TWWForms\Includes;

class TWW_User extends \MeprUser {
    const MEMBERSHIP_GROUP_NAME = 'TWW+';    
    private $group_name = null;
    private $user_id = null;

    public function __construct($user_id = null) {
        $this->user_id = $user_id ?? get_current_user_id();

        parent::__construct($this->user_id);

        $this->set_group_name();
    }

    public function get_last_subscription() {
        $subscriptions = $this->subscriptions();

        if($subscriptions) {
            return $subscriptions[0];
        }

        return null;
    }

    public function subscriptions() {
        if(!class_exists('MeprDb')) {
            return null;
        }

        $mepr_current_user = \MeprUtils::get_currentuserinfo();
        if(!$mepr_current_user) {
            return null;
        }

        $perpage = \MeprHooks::apply_filters('mepr_subscriptions_per_page', 10);
        $curr_page = 1;

        $sub_cols = array('id','user_id','product_id','subscr_id','status','created_at','expires_at','active');

        $table = \MeprSubscription::account_subscr_table(
        'created_at', 'DESC',
        $curr_page, '', 'any', $perpage, false,
        array(
            'member' => $mepr_current_user->user_login,
            'statuses' => array(
            \MeprSubscription::$active_str,
            \MeprSubscription::$suspended_str,
            \MeprSubscription::$cancelled_str
            )
        ),
        $sub_cols
        );

        return $table['results'] ?? null;
    }
}
