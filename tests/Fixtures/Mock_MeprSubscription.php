<?php

namespace TWWFormsTests\Fixtures;

class Mock_MeprSubscription extends \MeprSubscription {
    public function __construct() {
        parent::__construct([
            'id'                  => 511,
            'subscr_id'           => 'mp-sub-veryunique',
            'gateway'             => 'manual',
            'user_id'             => 1,
            'product_id'          => 1,
            'status'              => 'active',
            'created_at'          => '2023-09-01 24:04:14',
            'total'               => 11.99,
            'tax_class'           => 'standard',
            'cc_last4'            => 1111,
            'cc_exp_month'        => 10,
            'cc_exp_year'         => 2028,
            'token'               => 'tokenrandom',
            'order_id'            => 2,
        ]);
    }

    protected function mgm_first_txn_id($mgm, $val = '') {
        return 101;
    }

    public function first_txn() {
        return new Mock_MeprTransaction();
    }
}