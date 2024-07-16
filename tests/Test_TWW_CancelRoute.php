<?php
use TWWForms\Routes\TWW_CancelRoute;

class Test_TWW_CancelRoute extends WP_UnitTestCase {
    private $subscription;
    
    public function setUp(): void {
        $this->subscription = new MeprSubscription([
            'id' => 222,
            'status' => 'stopped',
        ]);

        $this->active_subscription = new MeprSubscription([
            'id' => 111,
            'status' => 'enabled',
        ]);
    }

    /**
     * @covers TWW_CancelRoute::__construct
     * @group cancel-subscription
     */
    public function test_instance_of_tww_cancel_route() {
        $cancelRoute = new TWW_CancelRoute();
        $this->assertInstanceOf(TWW_CancelRoute::class, $cancelRoute);
    }

    /**
     * @covers TWW_CancelRoute::cancel_subscription
     * @group cancel-subscription
     */
    public function test_missing_subscription_id_will_return_wp_error() {
        $cancelRoute = new TWW_CancelRoute();
        $request = new \WP_REST_Request('POST', '/cancel-subscription');
        $request->set_param('active_subscription_id', null);
        $response = $cancelRoute->cancel_subscription($request);
        $this->assertInstanceOf('WP_Error', $response);
        $this->assertEquals('missing_id', $response->get_error_code());
    }

    /**
     * @covers TWW_CancelRoute::cancel_subscription
     * @group cancel-subscription
     */
    public function test_non_active_subscription_will_return_wp_error() {
        $cancelRoute = new TWW_CancelRoute();
        $request = new \WP_REST_Request('POST', '/cancel-subscription');
        $request->set_param('active_subscription_id', 222);
        $response = $cancelRoute->cancel_subscription($request);
        $this->assertInstanceOf('WP_Error', $response);
        $this->assertEquals('subscription_not_active', $response->get_error_code());
    }
}