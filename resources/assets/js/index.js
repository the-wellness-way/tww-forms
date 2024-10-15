import { getEl, clearErrors, successDiv, errorDiv, validateEmail } from "./helpers.js";
import { config } from "./config.js";
import { state, setState } from "./state.js";
import { twwLoaderSVG } from "./loader.js";

import { initChangePasswordForm, changePasswordRequest } from "./shortcodes/tww-change-password-form-shortcode.js";
import { initEditUserName } from "./shortcodes/tww-edit-username.js";
import { initCancelSubscription, initUpdateSubscription, initResumeWithCreateTransactionButton, initChangePlanModal } from "./shortcodes/tww-current-membership-shortcode.js";
import { initForm } from "./shortcodes/tww-free-subscription-shortcode.js";
import { initLoginModal, login, createLoginFlow, createLoginFields, createMeprRegistrationFields } from "./components/modal-login.js";
import { createMember } from "./shortcodes/tww-free-subscription-shortcode.js";
import { initGrams2OuncesShortcode } from "./shortcodes/tww-grams2ounces-shortcode.js"

(function() {
    console.log('TWW Forms loaded');
    initChangePasswordForm();
    initEditUserName();
    initCancelSubscription();
    initUpdateSubscription();
    initResumeWithCreateTransactionButton();
    initChangePlanModal();
    initForm();
    initLoginModal();
    initGrams2OuncesShortcode();

    window.validateEmail = validateEmail;
    window.state = state;
    window.setState = setState;
    window.config = config;
    window.twwLoaderSVG = twwLoaderSVG;
    window.twwLogin = login;
    window.getEl = getEl;
    window.errorDiv = errorDiv;
    window.successDiv = successDiv;
    window.clearErrors = clearErrors;
    window.createMember = createMember;
    window.createLoginFlow = createLoginFlow;
    window.createLoginFields = createLoginFields;
    window.createMeprRegistrationFields = createMeprRegistrationFields;
    window.changePasswordRequest = changePasswordRequest;
    window.login = login;
}());
