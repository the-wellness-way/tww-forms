<?php
$prods = new \WP_Query([
    'post_type' => 'memberpressproduct',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);
echo '<div class="can-current-membership-wrapper">';
echo '<div class="can-current-membership-inner">';
?>
    <div class="can-current-membership-header">
        <h2>Your Plan</h2>
        <a class="button recurring" href="/tww-membership?action=update&sub=<?php echo $subscription->id; ?>">YEARLY</a>
    </div>

    <div class="can-current-membership-membership-string">
        <?php echo $this->print_membership_string(); ?>
    </div>

<?php
if ($prods->have_posts()) {
    foreach($prods->posts as $canprod) {
        $active_product_id = null;

        if($canprod->ID == $subscription->product_id) {
            $active_product_id = $canprod->ID;
        }
        ?>
                <div class="can-current-membership <?php echo $active_product_id ? 'ccm-active' : ''; ?>">
                    <div class="can-current-membership--inner">

                        <div class="can-current-membership--radio">
                            <div class="can-current-membership--circle-wrapper">
                                <div class="can-current-membership--circle"></div>
                            </div>
                        </div>
                        
                        <div class="can-current-membership--content">
                            <div class="can-current-membership--header">
                                <span><?php echo $canprod->post_title; ?> Membership</span>
                                <h2><?php echo $canprod->post_title; ?></h2>
                                <?php
                                if($active_product_id) : ?>
                                    <span class="status-tag <?php echo $subscription->status; ?>"><?php echo $active_product_id ? 'Your Current Plan' : ''; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
    }
}
echo '</div>';
echo '</div>';



if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');

    error_log('sub: ' . print_r($sub, true));
}

// Check if the current URL is '/account'
if (strpos($_SERVER['REQUEST_URI'], '/tww-membership') !== false) {

    // Check if the 'MeprAccountCtrl' class exists
    if (class_exists('MeprAccountCtrl')) {
        // Instantiate the class
        $accountCtrl = new MeprAccountCtrl();

        // Check if the 'action' is set and equals 'update'
        if (isset($_GET['action']) && $_GET['action'] === 'update') {
            // Run the update function from the class

            $sub = new MeprSubscription($_REQUEST['sub']);


            if($sub->payment_method()) {
                $accountCtrl->update();
            }
        } else {
            // If action is not 'update', load the custom template (your original content)
            ?>
            <div id="tww-api-response"></div>
            <div id="tww-current-membership-shortcode" class="current-membership">
                <div class="current-membership--inner">
                    <span class="tag">current plan</span>
                    <div class="current-membership--header">
                        <?php echo $this->print_title(); ?>
                        <?php echo $this->print_status_tag(); ?>
                    </div>

                    <div class="membership">
                        <?php echo $this->print_membership_string(); ?>

                        <div class="current-membership--actions">
                            <?php echo $this->print_actions(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($prd->group()): ?>
            <div>
                <div id="tww-change-plan-modal" class="tww-change-plan-modal">
                    <div class="tww-change-plan-modal--inner">
                    <div class="tww-change-plan-modal-close--wrapper">
                        <a href="#" class="tww-change-plan-modal-close">Close</a>
                    </div>
                    
                    <h2>Change Plan</h2>
                    <p>Choose a new plan</p>
                    
                    <select id="tww-change-plan-selection" class="mepr-upgrade-dropdown">
                        <?php 
                            $count = 0;
                            foreach ($prd->group()->products() as $product) : 
                                $useRest = $count == 0  ? 'data-use-rest=true' : '';
                            ?>
                            <?php if ($product->can_you_buy_me()): ?>
                            <option <?php echo $useRest; ?> <?php echo $subscription->product()->post_title === $product->post_title ? 'selected' : ''; ?>  value="<?php echo $product->url(); ?>">
                                <?php 
                                $product_terms = '';
                                
                                if ($product->ID) {
                                    $user = new \MeprUser($user_id);    
                                    $product_terms = \MeprProductsHelper::product_terms($product, $user);
                                    $product_terms = $count != 0 ? '(' . $product_terms . ')' : ''; 
                                }
                                
                                $count++;
                                printf('%1$s %2$s', $product->post_title, $product_terms); 
                                ?>
                            </option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <div class="tww-change-plan--actions">
                        <button id="tww-change-plan-selection-button" class="tww-primary-button">Change Plan</button>
                    </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php
        }
    } 
} else {
    // If the URL is not '/account', load the default template behavior (your custom template)
    ?>
    <div id="tww-api-response"></div>
    <div id="tww-current-membership-shortcode" class="current-membership">
        <div class="current-membership--inner">
            <span class="tag">current plan</span>
            <div class="current-membership--header">
                <?php echo $this->print_title(); ?>
                <?php echo $this->print_status_tag(); ?>
            </div>

            <div class="membership">
                <?php echo $this->print_membership_string(); ?>
            
                <div class="current-membership--actions">
                    <?php echo $this->print_actions(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($prd->group()): ?>
    <div>
        <div id="tww-change-plan-modal" class="tww-change-plan-modal">
            <div class="tww-change-plan-modal--inner">
            <div class="tww-change-plan-modal-close--wrapper">
                <a href="#" class="tww-change-plan-modal-close">Close</a>
            </div>
            
            <h2>Change Plan</h2>
            <p>Choose a new plan</p>
            
            <select id="tww-change-plan-selection" class="mepr-upgrade-dropdown">
                <?php 
                    $count = 0;
                    foreach($prd->group()->products() as $product) : 
                        $useRest = $count == 0  ? 'data-use-rest=true' : '';
                    ?>
                    <?php if($product->can_you_buy_me()): ?>
                    <option <?php echo $useRest; ?> <?php echo $subscription->product()->post_title === $product->post_title ? 'selected' : ''; ?>  value="<?php echo $product->url(); ?>">
                        <?php 
                        $product_terms = '';
                        
                        if ($product->ID) {
                            $user = new \MeprUser($user_id);    
                            $product_terms = \MeprProductsHelper::product_terms($product, $user);
                            $product_terms = $count != 0 ? '(' . $product_terms . ')' : ''; 
                        }
                        
                        $count++;
                        printf('%1$s %2$s', $product->post_title, $product_terms); 
                        ?>
                    </option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <div class="tww-change-plan--actions">
                <button id="tww-change-plan-selection-button" class="tww-primary-button">Change Plan</button>
            </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php
}
?>
