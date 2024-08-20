import { config } from './config.js';

export const state = {
    restNonce: window.twwForms.restNonce,
    restUrl: window.twwForms.siteUrl + '/wp-json/tww/v1',
    siteUrl: window.twwForms.siteUrl,
    assetsPath: window.twwForms.assetsPath,
    iconsPath: window.twwForms.iconsPath,
    activeSubscriptionId: window.twwForms.active_subscription_id,
    subscriptionCreatedAt: window.twwForms.subscription_created_at,
    subscriptionStatus: window.twwForms.subscription_status,
    membershipId: window.twwForms.membership_id,
    subscriptionExpired: window.twwForms.subscriptionExpired,
    currentUserId: window.twwForms.current_user_id,
    forgotPasswordUrl: window.twwForms.forgotPasswordUrl,
    endpoints: {
        createTransaction: config.restUrl + '/create-transaction',
        updateSubscription: config.restUrl + '/update-subscription',
        cancelSubscription: config.restUrl + '/cancel-subscription',
        resumeSubscription: config.restUrl + '/update-subscription',
        createTransaction: config.restUrl + '/create-transaction',
        updateUser: config.restUrl + '/update-user',
        createMember: config.restUrl + '/create-member',
        login: config.restUrl + '/login',
        changePassword: config.restUrl + '/change-password',
    }
}

export const setState = (key, value) => {
    state[key] = value;
}
