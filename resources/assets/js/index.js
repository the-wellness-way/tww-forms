import { getEl, clearErrors } from "./helpers.js";
import { config } from "./config.js";
import { state } from "./state.js";

import { initChangePasswordForm } from "./shortcodes/tww-change-password-form-shortcode.js";
import { initEditUserName } from "./shortcodes/tww-edit-username.js";
import { initCancelSubscription, initUpdateSubscription, initResumeWithCreateTransactionButton, initChangePlanModal } from "./shortcodes/tww-current-membership-shortcode.js";
import { initForm } from "./shortcodes/tww-free-subscription-shortcode.js";
import { initLoginModal } from "./components/modal-login.js";
import { initGrams2OuncesShortcode } from "./shortcodes/tww-grams2ounces-shortcode.js"

(function() {
    console.log("help")
    initChangePasswordForm();
    initEditUserName();
    initCancelSubscription();
    initUpdateSubscription();
    initResumeWithCreateTransactionButton();
    initChangePlanModal();
    initForm();
    initLoginModal();
    initGrams2OuncesShortcode();
})();
