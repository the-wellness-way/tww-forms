const twwLoaderSVG = 'loader-rings-white';

const initForm = () => {  
    if(getEl(config.twwRegistrationFree)){
        clearErrors();
        clearSuccess();
        

        getEl(config.twwRegistrationFree).addEventListener('submit', (e) => {
            e.preventDefault();

            clearErrors()
            clearSuccess();

            let email = getEl(config.twwPlusEmail).value ?? null;

            if(email) {
                if(getEl(config.twwButtonLoader)) {
                    getEl(config.twwButtonLoader).innerHTML = `<img src="${state.iconsPath}/${twwLoaderSVG}.svg" alt="Loading...">`;
                }

                if(getEl(config.twwSubscribeButtonText)) {
                    getEl(config.twwSubscribeButtonText).style.visibility = 'hidden';
                }

                createMember({email: email, username: email}).then((response) => {
                    console.log(response)

                    if(getEl(config.twwSubscribeButtonText)) {
                        getEl(config.twwSubscribeButtonText).style.visibility = 'visible';
                    }

                    if(getEl(config.twwButtonLoader)) {
                        getEl(config.twwButtonLoader).innerHTML = '';
                    }

                    if(response.message && 'success' === response.status){
                        // getEl(config.twwRegistrationFree).appendChild(successDivAlt(response.message));
                    } else if (response.data && 400 === response.data.status) {
                        getEl(config.twwRegistrationFree).appendChild(errorDiv(response.message));
                    }
    
                    if(response.data && response.data.id && response.data.latest_txn) {
                        // getEl(config.twwRegistrationFree).appendChild(successDivAlt('You have successfully registered. Please check your email for your password.'));

                        getEl(config.twwRegistrationFree).appendChild(successDivAlt(response.message + ' Reloading page.'))

                        if(response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    }
    
                }).catch((error) => {             
                    getEl(config.twwRegistrationFree).appendChild(errorDiv(error.message));
                });
            } else {        
                getEl(config.twwPlusEmailError).appendChild(errorDiv('Please enter a valid email address.'));
            }           
        });

    }
}

const createMember = async (data) => {
    const response = await fetch(state.endpoints.createMember, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': state.restNonce,
        },
        body: JSON.stringify(data),
    });

    return await response.json();
}

const successDivAlt = (message) => {   
    const div = document.createElement('div');
    div.id = 'success-message';
    div.classList.add('tww-plus-success');
    div.style.color = 'green';

    const p = document.createDocumentFragment('p');
    p.textContent = message;

    div.appendChild(p);

    div.innerHTML = message;

    return div;
}

(function(){
    initForm();
})();