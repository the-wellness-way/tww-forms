<<<<<<< HEAD
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
=======
const initForm = () => {  
    if(getEl(config.twwRegistrationFree)) {
        clearErrors();
        clearErrors('.error-message', true);
        clearSuccess();

        const subscribeForms = getAll('tww-plus-subscribe-form');
        subscribeForms.forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                clearErrors();
                clearErrors('.error-message', true);
                clearSuccess();

                
                let closestParent = e.target.closest('.tww-plus-subscribe-form');
                let email = closestParent.querySelector('#' + config.twwPlusEmail).value ?? null;

                if(email) {
                    let buttonLoader = closestParent.querySelector('#tww-plus-button-loader');
                    if(buttonLoader) {
                        buttonLoader.innerHTML = `<img src="${state.iconsPath}/${twwLoaderSVG}.svg" alt="Loading...">`;
                    }

                    let button = closestParent.querySelector('#' + config.twwSubscribeButtonText);
                    if(button) {
                        button.style.visibility = 'hidden';
                    }

                    createMember({ email: email, username: email }).then((response) => {
                        if(button) {
                            button.style.visibility = 'visible';
                        }

                        if(buttonLoader) {
                            buttonLoader.innerHTML = '';
                        }

                        if(response.message && 'success' === response.status && response.data && response.data.id) {
                            closestParent.appendChild(successDivAlt(response.message + ' Reloading page.'));

                            if(response.redirect_url) {
                                window.location.href = response.redirect_url;
                            } else {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        } else if (response.data && 400 === response.data.status) {
                            if(response.code && 'member_exists' === response.code) {
                                const fields = createLoginFields(email);
                                const message = successDivAlt('You are already registered. Please login or reset your password.');
                                createPasswordModal(fields, message);
                            } else {
                                //refactor getEl(config.twwRegistrationFree).appendChild(errorDiv(response.message)); to closest form parent
                                // so e.target.id can be used to target the form
                                let closestParent = e.target.closest('.tww-plus-subscribe-form');
                                closestParent.appendChild(errorDiv(response.message));
                            }
                        }
                    }).catch((error) => {             
                        getEl(config.twwRegistrationFree).appendChild(errorDiv(error.message));
                    });
                } else {        
                    let closestParent = e.target.closest('.tww-plus-subscribe-form');
                    closestParent.appendChild(errorDiv('Please enter a valid email address.'));
                }           
            });
        });
    }
};

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

const login = async (data) => {
    const response = await fetch(state.endpoints.login, {
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

const createPasswordModal = (fields , message, email) => {
    const modal = document.createElement('div');
    modal.id = 'tww-plus-password-modal';
    modal.classList.add('tww-plus-modal');
    modal.classList.add('is-open');

    const modalInner = document.createElement('div');
    modalInner.id = 'tww-plus-modal-inner';
    modalInner.classList.add('tww-plus-modal__inner');

    const modalContent = document.createElement('div');
    modalContent.classList.add('tww-plus-modal__content');

    const close = document.createElement('span');
    close.classList.add('tww-plus-modal__close');
    close.innerHTML = 'Close';

    const h2 = document.createElement('h2');
    h2.textContent = 'Login';

    const p = document.createElement('div');
    p.classList.add('tww-plus-modal__message');
    //check if message is a element or string
    if(typeof message === 'object') {
        p.appendChild(message);
    } else {    
        p.textContent = message ?? 'Please enter your password to login.';
    }

    modalContent.appendChild(close);
    modalContent.appendChild(h2);
    modalContent.appendChild(p);

    if(fields) {
        modalContent.appendChild(fields);
    }

    modal.appendChild(modalInner);
    modalInner.appendChild(modalContent);

    document.body.appendChild(modal);

    close.addEventListener('click', () => {
        let is_open = modal.classList.contains('is-open');
        if(is_open) {
            modal.classList.remove('is-open');
        } else {
            modal.classList.add('is-open');
        }
    });

    window.addEventListener('click', (e) => {
        if(e.target === modal) {
            let is_open = modal.classList.contains('is-open');
            if(is_open) {
                modal.classList.remove('is-open');
            } else {
                modal.classList.add('is-open');
            }
        }
    });
}

const createLoginFields = (email_address = '') => {
    const loginForm = document.createElement('form');
    loginForm.id = config.twwLoginForm;

    const loginFields = document.createElement('div');
    loginFields.classList.add('tww-plus-login-fields');

    const emailWrapper = document.createElement('div');
    emailWrapper.classList.add('tww-plus-login__fields-wrapper');
    const emailLabel = document.createElement('label');
    emailLabel.for = 'email';
    emailLabel.textContent = 'Email';

    const pwdWrapper = document.createElement('div');
    pwdWrapper.classList.add('tww-plus-login__fields-wrapper');
    pwdWrapper.classList.add('tww-plus-login__fields-wrapper--password');
    const pwdLabel = document.createElement('label');
    pwdLabel.for = 'password';
    pwdLabel.textContent = 'Password';

    const submitWrapper = document.createElement('div');
    submitWrapper.classList.add('tww-plus-login__fields-wrapper');
    submitWrapper.classList.add('tww-plus-login__submit-wrapper');

    const forgotPwdWrapper = document.createElement('div');
    forgotPwdWrapper.classList.add('tww-plus-login__fields-wrapper');

    const email = document.createElement('input');
    email.type = 'email';
    email.name = 'email';
    email.id = 'tww-plus-login-email';
    email.value = email_address;
    email.placeholder = 'Email';

    email.addEventListener('blur', (e) => {
        if(!validateEmail(e.target.value)) {
            e.target.classList.add('invalid');
        } else {
            e.target.classList.remove('invalid');
        }
    });

    const password = document.createElement('input');
    password.type = 'password';
    password.name = 'password';
    password.id = 'tww-plus-login-password';
    password.placeholder = 'Password';
    password.classList.add('tww-plus-login__password');

    const passwordEyeBtn = document.createElement('button');
    passwordEyeBtn.classList.add('tww-plus-password-eye-btn');
    passwordEyeBtn.type = 'button';

    const passwordEye = document.createElement('span');
    passwordEye.classList.add('tww-plus-password-eye');
    passwordEye.classList.add('dashicons');
    passwordEye.classList.add('dashicons-hidden');

    passwordEyeBtn.appendChild(passwordEye);

    passwordEyeBtn.addEventListener('click', () => {
        //check if password is visible
        if('password' === password.type) {
            password.type = 'text';
            passwordEye.classList.add('dashicons-visibility');
            passwordEye.classList.remove('dashicons-hidden');
        }
        else {
            password.type = 'password';
            passwordEye.classList.remove('dashicons-visibility');
            passwordEye.classList.add('dashicons-hidden');
        }
    })

    const submit = document.createElement('button');
    submit.type = 'submit';
    submit.id = config.twwLoginButton;
    submit.classList.add('loader-default');
    submit.classList.add('loader-default--primary');
    submit.classList.add('loader-default--full');
    submit.style.marginTop = '10px';

    if(!password.value) {
        submit.disabled = true;
    }

    const buttonLoader = document.createElement('div');
    buttonLoader.classList.add('loader-default--inner');


    const spanLoader = document.createElement('span');
    spanLoader.classList.add('button-text');
    spanLoader.textContent = 'Login';

    submit.appendChild(buttonLoader);
    submit.appendChild(spanLoader);

    loginForm.addEventListener('input', (e) => {
        e.preventDefault();

        if(email.value && password.value) {
            submit.disabled = false;
        } else {
            submit.disabled = true;
        }
    });

    loginForm.addEventListener('submit', (e) => {
        let email_value = email.value;
        let password_value = password.value;

        e.preventDefault();


        if(document.querySelector('#' + e.target.id).querySelector('.loader-default--inner')) {
            document.querySelector('#' + e.target.id).querySelector('.loader-default--inner').innerHTML = `<img src="${state.iconsPath}/${twwLoaderSVG}.svg" alt="Loading...">`;
        }

        if(email_value && password_value) {
            //if the login function is defined call it
            if(typeof login === 'function') {
                if(buttonLoader && spanLoader) {
                    buttonLoader.innerHTML = `<img src="${state.iconsPath}/${twwLoaderSVG}.svg" alt="Loading...">`;
                    spanLoader.textContent = '';
                }

                clearErrors('#error-message', true);
                login({email: email_value, password: password_value}).then(response => {
                    if(response.success && response.message) {
                        getEl('tww-plus-modal-inner').appendChild(successDiv(response.message));
                        window.location.reload();
                    } else {
                        //refactor getEl('tww-plus-modal-inner').appendChild(errorDiv(response.message)); to closest form parent
                        getEl('tww-plus-modal-inner').appendChild(errorDiv(response.message));

                        
                    }

                    if(buttonLoader && spanLoader) {
                        buttonLoader.innerHTML = '';
                        spanLoader.textContent = 'Login';
                    }
                }).catch(error => {
                    getEl('tww-plus-modal-inner').appendChild(errorDiv(error.message));

                    if(document.querySelector('#' + e.target.id).querySelector('.loader-default--inner')) {
                        document.querySelector('#' + e.target.id).querySelector('.loader-default--inner').innerHTML = `<img src="${state.iconsPath}/${twwLoaderSVG}.svg" alt="Loading...">`;
                    }
                });
            }
        }
    });

    const forgotPassword = document.createElement('a');
    forgotPassword.id = 'forgot-password-link';
    forgotPassword.href = state.siteUrl + '/login/?action=forgot_password';
    forgotPassword.textContent = 'Forgot password?';


   // emailWrapper.appendChild(emailLabel);
    emailWrapper.appendChild(email);
   // pwdWrapper.appendChild(pwdLabel);
    pwdWrapper.appendChild(password);
    pwdWrapper.appendChild(passwordEyeBtn);
    submitWrapper.appendChild(submit);
    forgotPwdWrapper.appendChild(forgotPassword);

    loginFields.appendChild(emailWrapper);
    loginFields.appendChild(pwdWrapper);
    loginFields.appendChild(submitWrapper);
    loginFields.appendChild(forgotPwdWrapper);

    loginForm.appendChild(loginFields);

    return loginForm;
}


(function(){
    initForm();
>>>>>>> merge
})();