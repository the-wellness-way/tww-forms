import { getEl, clearErrors } from "./helpers.js";
import { config } from "./config.js";
import { state } from "./state.js";

import { initChangePasswordForm } from "./shortcodes/tww-change-password-form-shortcode.js";
import { initEditUserName } from "./shortcodes/tww-edit-username.js";
import { initCancelSubscription, initChangePlanModal } from "./shortcodes/tww-current-membership-shortcode.js";
import { initForm } from "./shortcodes/tww-free-subscription-shortcode.js";
import { initLoginModal } from "./components/modal-login.js";
import { initGrams2OuncesShortcode } from "./shortcodes/tww-grams2ounces-shortcode.js"

(function() {
    initChangePasswordForm();
    initEditUserName();
    initCancelSubscription();
    initChangePlanModal();
    initForm();
    initLoginModal();
    initGrams2OuncesShortcode();
})();
