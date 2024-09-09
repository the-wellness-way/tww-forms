<?php
use TWWForms\Routes\TWW_SubscriptionRoute;

class Test_TWW_SubscriptionRoute extends WP_UnitTestCase {
    private $rejected_response;
    private $success;

    public function setUp(): void {
        $this->rejected_response = [
            'headers' => array(),
            'cookies' => array(),
            'filename' => null,
            'response' => ['code' => 200],
            'status_code' => 200,
            'success' => 1,
            'body' => json_encode(['code' => 'mp_db_create_error']),
        ];

        $this->success = [
                'headers' => array(),
                'cookies' => array(),
                'filename' => null,
                'response' => ['code' => 200],
                'status_code' => 200,
                'success' => 1,
                'body' => json_encode([
                    'status' => 'success',
                    'message' => 'Member created successfully',
                    'redirect' => '',
                    'data' => []
                ]),
            ];
    }

    public function test_tww_register_routes() {
        $subRoute = new TWW_SubscriptionRoute();
        $this->assertInstanceOf(TWW_SubscriptionRoute::class, $subRoute);
    }

    /**
     * @covers TWW_SubscriptionRoute::create_member
     * @group twwCreateMember
     */
    public function test_rest_ensure_response() {
        add_filter('pre_http_request', function($preempt, $request, $url) {
            return $this->success;
        }, 10, 3);

        $subRoute = new TWW_SubscriptionRoute();
        $request = new \WP_REST_Request('POST', '/tww/v1/create-member');
        
        $request->set_query_params([
            'email' => 'me@philiparudy.com',
            'username' => 'me@philiparudy.com'
        ]);
        
        $response = $subRoute->create_member($request);

        $this->assertInstanceOf(WP_REST_Response::class, $response);
        $this->assertEquals('success', $response->data['status']);
    } 
}