const state = {
    restNonce: window.twwForms.restNonce,
    restUrl: window.twwForms.siteUrl + '/wp-json/tww/v1',
    assetsPath: window.twwForms.assetsPath,
    iconsPath: window.twwForms.iconsPath,
    activeSubscriptionId: window.twwForms.active_subscription_id,
    currentUserId: window.twwForms.current_user_id,
    endpoints: {
        cancelSubscription: config.restUrl + '/cancel-subscription',
        updateUser: config.restUrl + '/update-user',
        createMember: config.restUrl + '/create-member',
    }
}

const setState = (key, value) => {
    state[key] = value;
}