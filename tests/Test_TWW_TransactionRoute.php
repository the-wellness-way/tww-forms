<?php
use TWWForms\Routes\TWW_TransactionsRoute;

class Test_TWW_TransactionRoute extends WP_UnitTestCase {
    private $error_response;

    public function setUp(): void {
        $this->error_response = [
            'headers' => array(),
            'cookies' => array(),
            'filename' => null,
            'response' => [
                'code' => 'missing_params',
                'message' => 'Missing required parameters.',
                'data' => [
                    "status" => 400
                ]
            ],
            'status_code' => 400,
            'success' => 1,
            'body' => json_encode(['code' => 'mp_db_create_error']),
        ];
    }

    public function test_missing_parameters_return_wp_error() {
        $request = new \WP_REST_Request();

        $request->set_query_params([
            'missing' => 'all_params',
        ]);

        $twwTransaction = new TWW_TransactionsRoute();
        
        $this->assertInstanceOf(WP_Error::class, $twwTransaction->create_transaction($request));
    }
}