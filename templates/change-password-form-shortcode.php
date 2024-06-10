<?php
/**
 * Template for the change password form shortcode
 * 
 * @package TWWForms
 */
?>
<div class="change-password-form">
    <form id="tww-change-password-form" autocomplete="off">
        <div class="form-group">
            <label for="tww-current-password">Current Password <span class="required"><sup>*</sup></span></label>
            <div class="tww-password-wrapper">
                <input type="password" name="current_password" id="tww-current-password" value="" placeholder="Current Password" required autocomplete="new-password" />
                <button class="tww-plus-password-eye-btn" type="button"><span class="tww-plus-password-eye dashicons dashicons-hidden"></span></button>
            </div>
        </div>

        <div class="tww-password-strength">
            <p>Password must be at least 8 characters long and contain at least one capital letter, one lowercase letter, and one number.</p>
            
            <div class="form-group">
                <label for="tww-new-password">New Password <span class="required"><sup>*</sup></span></label>
                <div class="tww-password-wrapper">
                    <input type="password" name="new_password" id="tww-new-password" value="" placeholder="New Password" required autocomplete="new-password"/>
                    <button class="tww-plus-password-eye-btn" type="button"><span class="tww-plus-password-eye dashicons dashicons-hidden"></span></button>
                </div>
            </div>
        
            <div class="form-group">
                <label for="tww-confirm-new-password">Confirm New Password <span class="required"><sup>*</sup></span></label>
                <div class="tww-password-wrapper">
                    <input type="password" name="confirm_password" id="tww-confirm-new-password" value="" placeholder="Confirm New Password" required autocomplete="new-password" />
                    <button class="tww-plus-password-eye-btn" type="button"><span class="tww-plus-password-eye dashicons dashicons-hidden"></span></button>
                </div>
            </div>
        </div>

        <button type="submit" id="tww-change-pw-button" class="tww-form-submit-button loader-default loader-default--primary" disabled>
            <div class="button-loader button-loader-absolute"></div> 
            <span class="tww-subscribe-button-text">Update</span>
        </button>
    </form>
</div>