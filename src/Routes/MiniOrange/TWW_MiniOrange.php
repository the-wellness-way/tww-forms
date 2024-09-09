<?php
namespace TWWForms\Routes\MiniOrange;

use TWWForms\Routes\TWW_Routes;
class TWW_MiniOrange {
    protected $routes = [
        'mo-login' => [
            'methods' => 'POST',
            'callback' => 'cancel_subscription',
            'path' => '/mo-login',
            'mp_path' => '/subscriptions/{id}/cancel',
        ]
    ];

    public function boot() {
        $this->register_routes();
    }

    public function post_to_mo_login() {
        $request = [
            'headers' => [

            ],
            'body' => [
                'username' => 'prudy@thewellnessway.com'
                ]
            ];
    }
}