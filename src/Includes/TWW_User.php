<<<<<<< HEAD
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

    public function set_group_name() {
        $this->group_name = self::MEMBERSHIP_GROUP_NAME;
    }

    public function is_active_tww_plus_member() {
        return $this->get_one_active_mem_id() ? true : false;
    }

    public function has_active_subscription($group_name = null) {
        $this->group_name = $group_name ?? $this->group_name;

        $active_sub_id = $this->get_one_active_sub_id_from_group();

        if(!class_exists('MeprSubscription') || !$active_sub_id) {
            return false;
        }

        $subscription = new \MeprSubscription($active_sub_id);

        $expires_at = $subscription->latest_txn()->expires_at;
        $status = $subscription->status;

        //compare the expires_at date to the current date with strtotime
        if(strtotime($expires_at) > time()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_one_sub_id_from_group($group_name = null) {
        $this->group_name = $group_name ?? $this->group_name;

        //Query memberpressproduct post type with post meta _mepr_group_id,
        // but first get the group id from a query with the group name

        $tww_sub_id = null;

        $group_query = new \WP_Query([
            'post_type' => 'memberpressgroup',
            'title' => $this->group_name,
        ]);

        if($group_query->have_posts()) {
            $group = $group_query->posts[0];
            $group_id = $group->ID;

            $product_query = new \WP_Query([
                'post_type' => 'memberpressproduct',
                'meta_query' => [
                    [
                        'key' => '_mepr_group_id',
                        'value' => $group_id,
                    ]
                ]
            ]);
        }

        return $tww_sub_id;
    }

    public function get_one_active_sub_id_from_group($group_name = null) {
        $this->group_name = $group_name ?? $this->group_name;

        $product_id = $this->get_one_active_mem_id();

        if(!class_exists('MeprDb') || !$product_id) {
            return null;
        }  

        $tww_sub_id = null;

        if($product_id) {
            $mepr_db = \MeprDb::fetch();

            $tww_sub = $mepr_db->get_one_record($mepr_db->subscriptions, [
                'product_id' => $product_id,
                'status' => 'active',
                'user_id' => $this->ID
            ]);

            if($tww_sub) {
                $tww_sub_id = $tww_sub->id;
            }
        }

        return $tww_sub_id;
    }

    public function get_one_active_mem_id($group_name = null) {
        $this->group_name = $group_name ?? $this->group_name;

        $active_product_id = null;
        $active_product_ids = $this->get_all_active_mem_ids($group_name);


        if(is_array($active_product_ids) && !empty($active_product_ids) && is_int(intval($active_product_ids[0]))) {
            $active_product_id = intval($active_product_ids[0]);
        }

        return $active_product_id;
    }

    public function get_all_active_mem_ids($group_name = null) {
        $this->group_name = $group_name ?? $this->group_name;

        add_filter('mepr-user-active-product-subscriptions', [$this, 'filter_active_memberships_by_group']);
        
        $active_product_ids = $this->active_product_subscriptions('ids');

        remove_filter('mepr-user-active-product-subscriptions', [$this, 'filter_active_memberships_by_group']);

        return $active_product_ids ?? null;
    }

    public function filter_active_memberships_by_group($product_ids) {
        $ids = null;

        if($this->group_name) {
            $ids = [];

            foreach($product_ids as $product_id) {
                $group_title = $this->get_group_title($product_id);
    
                if( $group_title === $this->group_name) {
                    $ids[] = $product_id;
                }
            }
        }

        return $ids ?? $product_ids;
    }

    public function get_group_title(int $product_id) {
        if(!class_exists('MeprProduct')) {
            return null;
        }

        $mp_product = $product_id ? new \MeprProduct($product_id) : null;


        return $mp_product ? $mp_product->group()->post_title : null;
    }
}
=======
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
>>>>>>> merge
