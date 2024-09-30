<?php
namespace TWWForms\Includes;
class TWW_MeprRenew {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'check_and_add_date_picker_to_meta_box']);
        
    }

    public function boot() {
        add_action('mepr_subscription_stored', [$this, 'set_custom_renewal_date_and_proration'], 10, 1);
    }

    public function set_custom_renewal_date_and_proration($subscription) {
            if(!($subscription instanceof \MeprSubscription)) {
                return;
            }

            // Set renewal date to October 1st of the current or next year
            $current_year = date('Y');
            $current_date = time();
            $october_first = strtotime("{$current_year}-10-01");

            // If the current date is past October 1st, use October 1st of next year
            if($current_date > $october_first) {
                $october_first = strtotime(($current_year + 1) . '-10-01');
            }

            // Calculate the number of days till October 1st for proration purposes
            $days_until_october = ($october_first - $current_date) / (60 * 60 * 24);

            // Calculate proration if needed
            $daily_rate = $subscription->price / 365; // Assuming annual pricing
            $prorated_amount = $days_until_october * $daily_rate;

            // Update the subscription expiration date to October 1st
            $subscription->expires_at = date('Y-m-d H:i:s', $october_first);

            // If proration is enabled, adjust the total and price for the subscription
            $subscription->total = $prorated_amount;
            $subscription->price = $prorated_amount;

            error_log(print_r($subscription, true));
            // Store the updated subscription with the new expiration date and price

    }

    public function check_and_add_date_picker_to_meta_box() {
        global $wp_meta_boxes;

        if (isset($wp_meta_boxes[\MeprProduct::$cpt]['side']['high']['memberpress-product-meta'])) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_date_picker_script']);
            add_action('add_meta_boxes', [$this, 'add_date_picker_to_memberpress_meta_box']);
        }
    }
    
    public function enqueue_date_picker_script() {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    }
    
    public function add_date_picker_to_memberpress_meta_box() {
        add_action('memberpress-product-meta-box', 'render_date_picker_field');
    }
    
    public function render_date_picker_field($post) {
        // Render the date picker input field without the year
        echo '<label for="membership_start_date">'. __('Start Date', 'memberpress') .'</label>';
        echo '<input type="text" id="membership_start_date" name="membership_start_date" class="datepicker" value="">';
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.datepicker').datepicker({
                    dateFormat : 'mm-dd', // Exclude the year
                    changeYear: false,    // Disable year selection
                    yearRange: false      // Don't show year dropdown
                });
            });
        </script>
        <?php
    }
}