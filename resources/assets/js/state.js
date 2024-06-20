import { config } from './config.js';

export const state = {
    restNonce: window.twwForms.restNonce,
    restUrl: window.twwForms.siteUrl + '/wp-json/tww/v1',
    siteUrl: window.twwForms.siteUrl,
    assetsPath: window.twwForms.assetsPath,
    iconsPath: window.twwForms.iconsPath,
    activeSubscriptionId: window.twwForms.active_subscription_id,
    currentUserId: window.twwForms.current_user_id,
    forgotPasswordUrl: window.twwForms.forgotPasswordUrl,
    endpoints: {
        cancelSubscription: config.restUrl + '/cancel-subscription',
        updateUser: config.restUrl + '/update-user',
        createMember: config.restUrl + '/create-member',
        login: config.restUrl + '/login',
        changePassword: config.restUrl + '/change-password',
    }
}

export const setState = (key, value) => {
    state[key] = value;
}
