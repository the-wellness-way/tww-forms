import { getEl, getAll, clearErrors, errorDiv, successDiv } from "../helpers.js";
import { config } from "../config.js";
import { state } from "../state.js";
import { twwLoaderSVG, loaderGif } from "../loader.js";

export const initCancelSubscription = () => {
    const cancelButton = getEl(config.twwCancelSubscription);
    const messageContainer = getEl(config.twwApiResponse);

    if(cancelButton) {
        cancelButton.addEventListener('click', async (e) => {
            e.preventDefault();
            e.target.appendChild(loaderGif());

            cancelSubscription().then((response) => {
                e.target.innerHTML = 'Cancel Membership';

                if(response.message && true == response.success){
                    messageContainer.appendChild(successDiv(response.message));
                } else if (response.message && (false == response.success || response.code)) {
                    messageContainer.appendChild(errorDiv("There was an error cancelling your subscription. Please try again later."));
                }
            }).catch((error) => {             
                messageContainer.appendChild(errorDiv("There was an error cancelling your subscription. Please try again later."));
            });
        });
    }
}

export const initResumeWithCreateTransactionButton = () => {
    const resumeWithCreateTransactionButton = getEl(config.twwResumeMembershipWithTransaction);
    const messageContainer = getEl(config.twwApiResponse);

    if(resumeWithCreateTransactionButton) {
        resumeWithCreateTransactionButton.addEventListener('click', async (e) => {
            e.preventDefault();
            e.target.appendChild(loaderGif());

            updateSubscription('active').then((response) => {
                if(response.message && true == response.success){
                    createTransaction().then((response) => {
                        if (response.message && true == response.success) {
                            messageContainer.appendChild(successDiv(response.message));
                        } else if (response.message && (false == response.success || response.code)) {
                            messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
                        }

                        document.querySelector('.' + config.loaders.loadinggif).remove();
                    }).catch((error) => {             
                        messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
                        document.querySelector('.' + config.loaders.loadinggif).remove();
                    });
                } else if (response.message && (false == response.success || response.code)) {
                    messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
                    document.querySelector('.' + config.loaders.loadinggif).remove();
                }
            }).catch((error) => {             
                messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
                document.querySelector('.' + config.loaders.loadinggif).remove();
            });
        });
    }
}

export const initUpdateSubscription = () => {
    const twwResumeMembershipButton = getEl(config.twwResumeMembershipButton);
    const messageContainer = getEl(config.twwApiResponse);

    if(twwResumeMembershipButton) {
        twwResumeMembershipButton.addEventListener('click', async(e) => {
            e.preventDefault();
            e.target.appendChild(loaderGif());

            updateSubscription('active').then((response) => {
                e.target.innerHTML = 'Resume Membership';

                if(response.message && true == response.success){
                    messageContainer.appendChild(successDiv(response.message));
                    window.location.reload();
                } else if (response.message && (false == response.success || response.code)) {
                    messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
                }
            }).catch((error) => {             
                messageContainer.appendChild(errorDiv("There was an error updating your membership. Please try again later."));
            });
        });
    }
}

export const cancelSubscription = async () => {
    let data = {
        active_subscription_id: state.activeSubscriptionId
    }
    
    const response = await fetch(state.endpoints.cancelSubscription, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': state.restNonce,
        },
    });

    return await response.json();
}

export const updateSubscription = async (status) => {
    if(!status) {
        console.log("missing status");
        return;
    }

    let data = {
        user_id: state.currentUserId,
        subscription_id: state.activeSubscriptionId,
        membership_id: state.membershipId,
        subscription_created_at: state.subscriptionCreatedAt,
        status: status,
    }

    const response = await fetch(state.endpoints.updateSubscription, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': state.restNonce,
        },
    });

    return await response.json();
}

export const createTransaction = async () => {
    let data = {
        user_id: state.currentUserId,
        subscription_id: state.activeSubscriptionId,
        membership_id: state.membershipId,
    }

    const response = await fetch(state.endpoints.createTransaction, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': state.restNonce,
        },
    });

    return await response.json();
}

export const initChangePlanModal = () => { 
    const changePlanModal = getEl(config.twwChangePlanModal);
    const changePlanButton = getEl(config.twwChangePlanButton);
    const changePlanModalButton = getEl(config.twwModalChangePlanButton);
    const changePlanModalClose = getAll(config.classList.twwchangePlanModalClose);

    if(changePlanModal) {
        if(changePlanButton) {
            changePlanButton.addEventListener('click', (e) => {
                e.preventDefault();
                changePlanModal.classList.add('is-open');
            });
        }
    
        if(changePlanModalClose) {
            changePlanModalClose.forEach(element => {
                element.addEventListener('click', (e) => {   
                    e.preventDefault();           
                    changePlanModal.classList.remove('is-open');
                })
            });
        }

        const changePlanSelection = getEl(config.twwChangePlanSelection);
        const changePlanSelectionButton = getEl(config.twwChangePlanSelectionButton);

        if(changePlanSelection && changePlanSelectionButton) {
            changePlanSelectionButton.addEventListener('click', (e) => {
                e.preventDefault();

                //redirect to url changePlanSelection option value 
                window.location.href = changePlanSelection.value;
            });
        }
    }
}

