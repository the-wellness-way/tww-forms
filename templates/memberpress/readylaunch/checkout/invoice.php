<?php if ( ! defined( 'ABSPATH' ) ) {
  die( 'You are not allowed to call this page directly.' );
}

$mepr_coupon_code = $coupon && isset($coupon->ID) ? $coupon->post_title : '';

if($mepr_coupon_code || ( is_object($tmpsub) && $tmpsub->prorated_trial ) ){
  unset( $sub_price_str );
}
?>

<div class="mp_wrapper mp_invoice">
  <!-- Subscription terms -->
  <?php if ( isset( $sub_price_str ) ) : ?>
    <div class="register-template__entry">
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php _ex( 'Terms:', 'ui', 'memberpress' ); ?></span></div>
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo $sub_price_str; ?></span></div>
    </div>
  <?php endif; ?>

  <!-- Loop through each invoice item -->
  <?php foreach ( $invoice['items'] as $item ) : ?>
    <div class="register-template__entry">
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo str_replace(MeprProductsHelper::renewal_str($prd), '', $item['description']); ?></span></div>
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo MeprAppHelper::format_currency( $item['amount'], true, false ); ?></span></div>
    </div>

    <!-- Display subscription price or one-time payment details -->
    <?php if(isset($txn, $sub) && !$txn->is_one_time_payment() && $sub instanceof MeprSubscription && $sub->id > 0) : ?>
      <div class="register-template__entry">
        <div><span class="twwe-skeleton-mount twwe-skeleton"><?php _e('Subscription Price:', 'memberpress'); ?></span></div>
        <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo MeprAppHelper::format_price_string($sub, $sub->price, true, $mepr_coupon_code); ?></span></div>
      </div>
    <?php elseif(!(isset($txn) && $txn->txn_type == 'sub_account')) : ?>
      <div class="register-template__entry">
        <div><span class="twwe-skeleton-mount twwe-skeleton"><?php _e('Product Price:', 'memberpress'); ?></span></div>
        <div><span class="twwe-skeleton-mount twwe-skeleton"><?php MeprProductsHelper::display_invoice( $prd, $mepr_coupon_code ); ?></span></div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

  <!-- Coupon discount -->
  <?php if ( isset( $invoice['coupon'] ) && ! empty( $invoice['coupon'] ) && $invoice['coupon']['id'] != 0 ) : ?>
    <div class="register-template__entry--discount">
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo $invoice['coupon']['desc']; ?></span></div>
      <div><span class="twwe-skeleton-mount"><?php echo MeprAppHelper::format_currency(MeprCouponsHelper::format_coupon_amount($invoice['coupon']['amount']), true, false); ?></span></div>
    </div>
  <?php endif; ?>

  <!-- Tax details -->
  <?php if ( $invoice['tax']['amount'] > 0.00 || $invoice['tax']['percent'] > 0 ) : ?>
    <div class="register-template__entry">
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php _ex( 'Sub-Total', 'ui', 'memberpress' ); ?></span></div>
      <div><span class="twwe-skeleton-mount"><?php echo MeprAppHelper::format_currency( $subtotal, true, false ); ?></span></div>
    </div>

    <div class="register-template__entry">
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo MeprUtils::format_tax_percent_for_display( $invoice['tax']['percent'] ) . '% ' . $invoice['tax']['type']; ?></span></div>
      <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo MeprAppHelper::format_currency( $invoice['tax']['amount'], true, false ); ?></span></div>
    </div>
  <?php endif; ?>

  <!-- Total amount -->
  <div class="register-template__entry register-template__entry--total">
    <div><span><?php _ex( 'Total:', 'ui', 'memberpress' ); ?></span></div>
    <div><span class="twwe-skeleton-mount twwe-skeleton"><?php echo MeprAppHelper::format_currency( $total, true, false ); ?></span></div>
    <input type="hidden" name="mepr_stripe_txn_amount" value="<?php echo MeprUtils::format_stripe_currency( $total ); ?>" />
  </div>
</div>
