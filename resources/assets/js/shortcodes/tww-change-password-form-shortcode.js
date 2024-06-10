const initChangePasswordForm = () => {
    const form = getEl(config.twwChangePasswordForm);
    clearErrors('#error-message', true);
    
    if (!form) {
        return;
    }

    form.addEventListener('input', async (event) => {
        console.log('event.target', event.target)
        if (event.target.type === 'password' && event.target.name !== 'current_password') {
            if (event.target.value.length > 0 && !validatePassword(event.target.value)) {
                event.target.classList.add('invalid');
                event.target.classList.remove('valid');
            } else {
                event.target.classList.remove('invalid');
                event.target.classList.add('valid');
            }
        }

        let newPassword = form.querySelector('input[name="new_password"]');
        let confirmPassword = form.querySelector('input[name="confirm_password"]');
        let submitButton = form.querySelector('button[type="submit"]');

        if (newPassword.value === confirmPassword.value && newPassword.classList.contains('valid') && confirmPassword.classList.contains('valid')) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;   
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors('#error-message', true);

        let closestParent = event.target.closest('#' + config.twwChangePasswordForm);
        let buttonLoader = closestParent.querySelector('.button-loader');

        if(buttonLoader) {
            buttonLoader.innerHTML = `<img src="${state.iconsPath}${twwLoaderSVG}.svg" alt="Loading...">`;
        }

        let button = closestParent.querySelector('.tww-subscribe-button-text');
        if(button) {
            button.style.visibility = 'hidden';
        }
    
        const formData = new FormData(form);
    
        changePasswordRequest({
            user_id: state.currentUserId,
            current_password: formData.get('current_password'),
            new_password: formData.get('new_password'),
            confirm_password: formData.get('confirm_password'),
        }).then(response => {
            if(button) {
                button.style.visibility = 'visible';
            }

            if(buttonLoader) {
                buttonLoader.innerHTML = '';
            }

            if (response.success) {
                form.appendChild(successDiv('Password updated successfully'));
            } else {
                form.appendChild(errorDiv(response.message));
            }
        }).catch(error => {
            form.appendChild(errorDiv('An error occurred. Please try again later.'));
        });
    });
}

//The password should contain at least 8 characters, one uppercase letter, one lowercase letter, and one number
const validatePassword = (password) => {
    const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

    return passwordRegex.test(password);
}

const changePasswordRequest = async (data) => {
    const response = await fetch(state.endpoints.changePassword, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
        },
    });

    return await response.json();
}

(function() {
    initChangePasswordForm();
})();