<?php
use TWWForms\Routes\TWW_StripeRoute;
use TWWFormsTests\Fixtures\Mock_MeprSubscription;

class Test_TWW_StripeRoute extends WP_UnitTestCase {
    /**
     * 
     */
    public function test_instance_of_class() {
        $this->assertInstanceOf(TWW_StripeRoute::class, new TWW_StripeRoute());
    }

    public function test_missing_subscription_id_returns_wp_error() {
        ///Build
        $request = new \WP_REST_Request('POST', 'tww/v1/create-stripe-subscription');
        $request->set_param('subscription_id', null);
        $request->set_param('user_id', 1);
        $request->set_param('membership_id', 1);

        //Operate
        $twwStripeRoute = new TWW_StripeRoute();
        $response = $twwStripeRoute->create_stripe_subcription($request);

        //Check
        $this->assertInstanceOf(\WP_Error::class, $response);
    }

    public function test_missing_user_id_returns_wp_error() {
        ///Build
        $request = new \WP_REST_Request('POST', 'tww/v1/create-stripe-subscription');
        $request->set_param('subscription_id', 1);
        $request->set_param('user_id', null);
        $request->set_param('membership_id', 1);

        //Operate
        $twwStripeRoute = new TWW_StripeRoute();
        $response = $twwStripeRoute->create_stripe_subcription($request);

        //Check
        $this->assertInstanceOf(\WP_Error::class, $response);
    }

    public function test_missing_membership_id_returns_wp_error() {
        ///Build
        $request = new \WP_REST_Request('POST', 'tww/v1/create-stripe-subscription');
        $request->set_param('subscription_id', 1);
        $request->set_param('user_id', 1);
        $request->set_param('membership_id', null);

        //Operate
        $twwStripeRoute = new TWW_StripeRoute();
        $response = $twwStripeRoute->create_stripe_subcription($request);

        //Check
        $this->assertInstanceOf(\WP_Error::class, $response);
    }
    
    public function test_get_subscription_error_logs() {
        //build
        $sub_id = '1233'; // not an existing subscription

        $routeMock = $this->getMockBuilder(TWW_StripeRoute::class)
        ->onlyMethods(['error_log'])
        ->getMock();

        $routeMock->expects($this->once())
         ->method('error_log');

         //operate
         $sub = $routeMock->get_subscription($sub_id);

         //check
         $this->assertNull($sub);
    }
}