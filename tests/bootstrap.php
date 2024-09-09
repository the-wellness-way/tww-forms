<?php
/**
 * PHPUnit bootstrap file
 */
require_once '/app/wp-tests-config.php';

$plugin_slug ='tww-forms';

require_once dirname(__DIR__) . '/vendor/autoload.php';

$WP_PHPUNIT_DIR = getenv('WP_PHPUNIT__DIR');

if (!$WP_PHPUNIT_DIR) {
    $WP_PHPUNIT_DIR = '/app/wp-content/plugins/'.$plugin_slug.'/vendor/wp-phpunit/wp-phpunit';
}

// Give access to tests_add_filter() function
require_once $WP_PHPUNIT_DIR . '/includes/functions.php';

tests_add_filter('muplugins_loaded', function() use ($plugin_slug) {
    update_option('mpdt_api_key', 'test_api_key');
    require '/app/wp-content/plugins/memberpress/memberpress.php';

    require dirname(__DIR__) . '/'.$plugin_slug.'.php';
});


require $WP_PHPUNIT_DIR . '/includes/bootstrap.php';