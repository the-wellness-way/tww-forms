<?php
/**
 * Plugin Name: TWW Forms V1
 * Description: Custom forms for TWW Plus registration
 * Version: 1.0
 * Author: The Wellness Way
 * Author URI: https://www.thewellnessway.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tww-forms
 * Domain Path: /languages
 */

 if(!defined('ABSPATH')) {
     exit;
 }

 if(!defined('TWW_FORMS_PLUGIN_FILE')) {
     define('TWW_FORMS_PLUGIN_FILE', __FILE__);
 }

 if(!defined('TWW_FORMS_PLUGIN')) {
     define('TWW_FORMS_PLUGIN', plugin_dir_path(__FILE__));
 }  

 if(!defined('TWW_FORMS_PLUGIN_URL')) {
     define('TWW_FORMS_PLUGIN_URL', plugin_dir_url(__FILE__));
 }

 if(!defined('TWW_FORMS_ASSETS_VERSION')) {
     define('TWW_FORMS_ASSETS_VERSION', '1.0.80');
 }

 require_once 'vendor/autoload.php';

if (!is_plugin_active('memberpress/memberpress.php')) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Error: MemberPress must be active for My Custom Plugin to function.</p></div>';
    });

    return;
}

class TWW_Forms {
    public function __construct() {
        // Memberpress hook to allow list stylesheets and scripts by handle
        add_filter('mepr_design_style_handles', [$this, 'tww_design_style_handle_prefixes']);
    }

    public function tww_design_style_handle_prefixes($allowed_handles) {
        $allowed_handles[] = 'tww-forms';

        return $allowed_handles;
    }
}

$twwForms = new TWW_Forms();

add_action('wp_enqueue_scripts', 'tww_register_styles');
function tww_register_styles() {
    $version = TWW_FORMS_ASSETS_VERSION;

    wp_register_style('tww-forms', TWW_FORMS_PLUGIN_URL . 'resources/assets/css/tww-forms.css', [], $version, 'all');
    wp_enqueue_style('tww-forms');
    wp_register_style('tww-forms-two', TWW_FORMS_PLUGIN_URL . 'resources/assets/css/tww-forms-two.css', [], $version, 'all');
    wp_enqueue_style('tww-forms-two');
}

use TWWForms\Controllers\TWW_SubscriptionsCtrl;

add_action('wp_enqueue_scripts', 'tww_register_scripts');
function tww_register_scripts() {
    $version = TWW_FORMS_ASSETS_VERSION;

    wp_register_script('tww-forms', TWW_FORMS_PLUGIN_URL . 'resources/assets/js/tww-forms.js', [], $version, true);
    wp_enqueue_script('tww-forms');
    wp_localize_script('tww-forms', 'twwForms', [
        'siteUrl' => site_url(),
        'iconsPath' => TWW_FORMS_PLUGIN_URL . 'resources/assets/images/icons/',
        'restNonce' => wp_create_nonce('wp_rest'),
        'active_subscription_id' => TWW_SubscriptionsCtrl::get_last_subscription_id() ?? null,
    ]);

    wp_register_script('tww-helpers', TWW_FORMS_PLUGIN_URL . 'resources/assets/js/helpers.js', [], $version, true);
    wp_register_script('tww-config', TWW_FORMS_PLUGIN_URL . 'resources/assets/js/config.js', [], $version, true);
    wp_register_script('tww-state', TWW_FORMS_PLUGIN_URL . 'resources/assets/js/state.js', [], $version, true);
    wp_register_script('tww-loader', TWW_FORMS_PLUGIN_URL . 'resources/assets/js/loader.js', [], $version, true);

    wp_enqueue_script('tww-helpers');
    wp_enqueue_script('tww-config');
    wp_enqueue_script('tww-state');
    wp_enqueue_script('tww-loader');
}

use TWWForms\Routes\TWW_SubscriptionRoute;
use TWWForms\Routes\TWW_CancelRoute;

$twwSubscriptionRoutes = new TWW_SubscriptionRoute();
add_action('rest_api_init', [$twwSubscriptionRoutes, 'boot']);

$twwCancelRoute = new TWW_CancelRoute();
add_action('rest_api_init', [$twwCancelRoute, 'boot']);

//use TWWForms\Includes\TWW_Email;

use TWWForms\Shortcodes\TWW_FreeShortcode;
use TWWForms\Shortcodes\TWW_MembershipShortcode;
use TWWForms\Shortcodes\TWW_EditUsernameShortcode;

add_action('init', function() {
    //$twwEmail = new TWW_Email();

    $subcripton_id  = TWW_SubscriptionsCtrl::get_last_subscription_id();
    $subcription    = new MeprSubscription($subcripton_id);
    $product        = new MeprProduct($subcription->product_id);
    $transaction    = new MeprTransaction($subcription->txn_id);

    $twwFreeShortcode = new TWW_FreeShortcode();
    $twwMembershipShortcode = new TWW_MembershipShortcode($product, $subcription, $transaction);
    $twwEditUsernameShortcode = new TWW_EditUsernameShortcode();
});


