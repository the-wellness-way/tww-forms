<?php
/**
 * Template for the free subscription form
 *
 * @package TWWForms
 */

 $justify = $atts['justify'] ?? 'flex-start';
?>

<div class="tww-registration-wrapper">
    <form id="tww-registration-free">
        <div class="tww-input-group">
            <div class="tww-form-field">
                <label for="email">Email *</label>
                <input id="tww-plus-email" type="email" value="" placeholder="Email" />
            </div>
        </div>
        <input type="hidden" id="tww-plus-post-id" value="<?php echo esc_attr($atts['post_id']); ?>" />
        <div class="tww-registration-submit" style="display:flex; justify:<?php echo $justify; ?>;">
            <button class="btn-tww-registration" type="submit">
                <div id="tww-plus-button-loader" class="button-loader button-loader-absolute">
                    <?php
                        // if (file_exists(TWW_FORMS_PLUGIN . 'resources/assets/images/icons/loader-rings-white.svg')) {
                        //     echo file_get_contents(TWW_FORMS_PLUGIN . 'resources/assets/images/icons/loader-rings-white.svg');
                        // } else {
                        //     echo 'Loading...';
                        // }
                    ?>
                </div> 
                <span id="tww-plus-subscribe-button-text">Subscribe</span>
            </button>
        </div>
    </form>
</div>