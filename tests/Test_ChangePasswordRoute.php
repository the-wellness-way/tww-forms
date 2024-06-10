<?php
namespace TWWForms\Tests;

use WP_UnitTestCase;
use TWWForms\Routes\TWW_ChangePasswordRoute;

class Test_ChangePasswordRoute extends WP_UnitTestCase {
    private $success;
    public function setUp() :void{ 
        $this->success = [
            'headers' => array(),
            'cookies' => array(),
            'filename' => null,
            'response' => ['code' => 200],
            'status_code' => 200,
            'success' => 1,
            'body' => json_encode([
                'status' => 'success',
            ]),
        ];
    }

    /**
     * @covers TWW_ChangePasswordRoute::class
     * @group changePassword
     */
    public function test_instance_of_TWW_ChangePasswordRoute() {    
        $this->assertInstanceOf(TWW_ChangePasswordRoute::class, new TWW_ChangePasswordRoute());
    }

    /**
     * TWW_ChangePasswordRoute::change_password
     * @group changePassword
     */
    public function test_invalid_user_id_param() {
        $json_data = [];

        $request = new \WP_REST_Request('POST','/tww/v1/change-password');
        $request->set_header( 'content-type', 'application/json' );
		$request->set_body( json_encode( $json_data ) );

        $twwChangePasswordRoute = new TWW_ChangePasswordRoute();

        $this->assertEquals(new \WP_Error('missing_params', 'Missing User ID.', ['status' => 400]), $twwChangePasswordRoute->change_password( $request ) );
    }

    /**
     * TWW_ChangePasswordRoute::change_password
     * @group changePassword
     */
    public function test_invalid_password_less_than_8() {
        $json_data = [
            'user_id' => 1,
            'current_password' => 'root',
            'new_password' => 'less8',
        ];

        $request = new \WP_REST_Request('POST','/tww/v1/change-password');
        $request->set_header( 'content-type', 'application/json' );
		$request->set_body( json_encode( $json_data ) );

        $twwChangePasswordRoute = new TWW_ChangePasswordRoute();

        $this->assertEquals(new \WP_Error('password_too_short', 'Password must be at least 8 characters long.', ['status' => 400]), $twwChangePasswordRoute->change_password( $request ) );
    }

    /**
     * TWW_ChangePasswordRoute::change_password
     * @group changePassword
     */
    public function test_invalid_new_password_param() {
        $json_data = [
            'user_id' => 1,
            'current_password' => 'root',
        ];

        $request = new \WP_REST_Request('POST','/tww/v1/change-password');
        $request->set_header( 'content-type', 'application/json' );
		$request->set_body( json_encode( $json_data ) );

        $twwChangePasswordRoute = new TWW_ChangePasswordRoute();

        $this->assertEquals(new \WP_Error('missing_params', 'Missing new password.', ['status' => 400]), $twwChangePasswordRoute->change_password( $request ) );
    }

    /**
     * TWW_ChangePasswordRoute::change_password
     * @group changePassword
     */
    public function test_invalid_password_nocapital_letter() {
        $json_data = [
            'user_id' => 1,
            'current_password' => 'root',
            'new_password' => 'thisismorethaneight'
        ];

        $request = new \WP_REST_Request('POST','/tww/v1/change-password');
        $request->set_header( 'content-type', 'application/json' );
		$request->set_body( json_encode( $json_data ) );

        $twwChangePasswordRoute = new TWW_ChangePasswordRoute();

        $this->assertEquals(new \WP_Error('password_invalid', 'Password must have at least one capital letter, one lowercase letter, and one number and be 8 or more characters.', ['status' => 400]), $twwChangePasswordRoute->change_password( $request ) );
    }
}
