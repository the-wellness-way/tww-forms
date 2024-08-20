<?php
namespace TWWForms\Routes;

class TWW_StatsRoute extends TWW_Routes {
    protected $routes = [
        'create-member' => [
            'methods' => 'POST',
            'callback' => 'create_stat',
            'path' => '/create-stat',
            'permission_callback' => '__return_true',
        ],
    ];

    public function boot() {
        $this->register_routes();
    }

    public function create_stat(\WP_REST_Request $request) {
        $params = $request->get_params();
    
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'tww_stats';
    
        // Insert the data
        $wpdb->insert(
            $table_name,
            [
                'user_id' => $params['user_id'],
                'stat_value' => $params['stat_value'],
                'stat_type' => $params['stat_type'],
                'created_at' => current_time('mysql'),
                'post_id' => $params['post_id'],
                'object_id' => $params['object_id'],
            ]
        );
    
        $insert_id = $wpdb->insert_id;
    
        $new_stat = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $insert_id));
    
        return $new_stat;
    }    
}