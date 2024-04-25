<?php
use TWWForms\Shortcodes\TWW_MembershipShortcode;


/**
 * 
 * 
 * We need to extend the MeprTransaction and MeprSubscription classes to ovverride magic methods that set and get properties
 */

class Test_MeprTransaction extends \MeprTransaction {
    public function __construct() {
        parent::__construct([
            'id'              => 101,
            'amount'          => 11.99,
            'total'           => 11.99,
            'status'          => 'complete',
            'txn_type'        => 'subscription_confirmation',
            'gateway'         => 'Stripe',
            'prorated'        => null,
            'created_at'      => date("Y-m-d H:i:s", time()),
            'expires_at'      => date("Y-m-d H:i:s", $this->time_day_ahead()), // should be less than 25 hours from created_at
            'subscription_id' => 44,
            'order_id' => 1,
        ]);
    }

    public function time_day_ahead() {
        // Add 86400 seconds (1 day) to the current time
        return time() + 86400;
    }
}

class Test_MeprSubscription extends \MeprSubscription {
    //
    protected function mgm_first_txn_id($mgm, $val = '') {
        return 101;
    }

    public function first_txn() {
        return new Test_MeprTransaction();
    }
}


class TWW_Test_MembershipShortcode extends WP_UnitTestCase {
    private $product;

    private $transaction;

    private $subscription;

    private $subscription_without_txn;

    private $transaction_subscription_confirmation;

    public function setUp() : void {
        $this->product = new MeprProduct([
            'id' => 99,
            'post_id' => 99,
            'name' => 'Test Product',
            'price' => 11.99,
        ]);

        $this->transaction = new MeprTransaction([
            'id'              => 55,
            'amount'          => 11.99,
            'total'           => 11.99,
            'status'          => 'complete',
            'txn_type'        => 'payment',
            'gateway'         => 'Stripe',
            'prorated'        => null,
            'created_at'      => '2024-09-01 24:04:14',
            'expires_at'      => '2024-10-01 24:04:14', // 0 = lifetime, null = default expiration for membership
            'subscription_id' => null,
            'order_id' => 1,
        ]);

        $this->transaction_subscription_confirmation = new Test_MeprTransaction();
        
        $this->subscription = new Test_MeprSubscription([
            'id'                  => 44,
            'subscr_id'           => 'mp-sub-'.uniqid(),
            'gateway'             => 'manual',
            'user_id'             => 1,
            'product_id'          => 1,
            'status'              => 'active',
            // I need a random string in datetime mysql format, for example '2021-09-01 00:00:00'
            'created_at'          => '2024-09-01 24:04:14',
            'total'               => 11.99,
            'tax_class'           => 'standard',
            'cc_last4'            => 1111,
            'cc_exp_month'        => 10,
            'cc_exp_year'         => 2028,
            'token'               => 'tokenrandom',
            // random yet
            'order_id'            => 1,
        ]);

        $this->subscription_without_txn = new MeprSubscription([
            'id'                  => 105,
            'subscr_id'           => 'mp-sub-'.uniqid(),
            'gateway'             => 'Manual',
            'user_id'             => 1,
            'product_id'          => 1,
            'status'              => 'active',
            // I need a random string in datetime mysql format, for example '2021-09-01 00:00:00'
            'created_at'          => '2024-09-01 24:04:14',
            'total'               => 11.99,
            'tax_class'           => 'standard',
            'cc_last4'            => 1111,
            'cc_exp_month'        => 10,
            'cc_exp_year'         => 2028,
            'token'               => 'tokenrandom',
            // random yet
            'order_id'            => 1,
        ]);
    }

    public function test_get_latest_subcription() {
        $user_id = 1;

        $subscription   = $this->subscription;
        $product        = $this->product;
        $transaction    = $this->transaction;

        $tww_membership = new TWW_MembershipShortcode($product, $subscription, $transaction);

        $this->assertInstanceof(TWW_MembershipShortcode::class, $tww_membership);
    }

    /**
     * @covers subscription_product
     * @group membershipShortcode
     */
    public function test_subscription_product() {
        $tww_membership = new TWW_MembershipShortcode(null, $this->subscription, null);

        $this->assertEquals($tww_membership->subscription_product(), $this->product);
    }

    /**
     * @covers get_subscription_latest_txn
     * @group membershipShortcode
     */
    public function test_get_subscription_latest_txn() {
        $twwMemeberShipMock = $this->getMockBuilder(TWW_MembershipShortcode::class)
            ->setConstructorArgs([null, $this->subscription, null])
            ->setMethods(['get_subscription_latest_txn'])
            ->getMock();

        $twwMemeberShipMock->expects($this->once())
            ->method('get_subscription_latest_txn')
            ->willReturn($this->transaction);

        $this->assertEquals($twwMemeberShipMock->get_subscription_latest_txn()->id, $this->transaction->id);
    }

    /**
     * @covers get_subscription_latest_txn
     * @group membershipShortcode
     */
    public function test_get_subscription_latest_txn_without_txn() {
        $twwMemeberShipMock = $this->getMockBuilder(TWW_MembershipShortcode::class)
            ->setConstructorArgs([null, $this->subscription_without_txn, null])
            ->setMethods(['get_subscription_latest_txn'])
            ->getMock();

        $twwMemeberShipMock->expects($this->once())
            ->method('get_subscription_latest_txn')
            ->willReturn(null);

        $this->assertEquals($twwMemeberShipMock->get_subscription_latest_txn(), null);
    }

    public function time_day_ahead() {
        // Add 86400 seconds (1 day) to the current time
        return time() + 86400;
    }

    public function test_subscription_in_grace_period() {
        $tww_membership = new TWW_MembershipShortcode(null, $this->subscription, $this->transaction_subscription_confirmation);

        $this->assertTrue($tww_membership->subscription_in_grace_period(), true);
    }
}

