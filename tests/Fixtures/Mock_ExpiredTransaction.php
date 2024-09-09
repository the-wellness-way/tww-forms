<?php

class Mock_Expired_MeprTransaction extends \MeprTransaction {
    public function __construct() {
        parent::__construct([
            'id'              => 51,
            'amount'          => 11.99,
            'total'           => 11.99,
            'status'          => 'complete',
            'txn_type'        => 'payment',
            'gateway'         => 'Stripe',
            'prorated'        => null,
            'created_at'      => date("Y-m-d H:i:s", $this->time_three_days_before()),
            'expires_at'      => date("Y-m-d H:i:s", $this->time_day_before()), // should be yesterday
            'subscription_id' => 51,
            'order_id' =>2,
        ]);
    }

    public function time_day_before() {
        // Add 86400 seconds (1 day) to the current time
        return time() - 86400 * 3;
    }

    public function time_three_days_before() {
        // Add 86400 seconds (1 day) to the current time
        return time() - 86400 * 4;
    }
}