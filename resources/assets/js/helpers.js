export const getEl = (el) =>  document.getElementById(el);
export const getAll = (className) => document.querySelectorAll('.'  + className);

export const errorDiv = (message) => {
    message = message ?? 'An error occurred. Please try again later.';

    const div = document.createElement('div');
    div.id = 'error-message';
    div.classList.add('error-message');
    div.style.color = 'red';

    const p = document.createElement('p');
    p.innerHTML = message;

    div.appendChild(p);

    return div;
}

export const successDiv = (message) => {
    message = message ?? 'Success!';

    const div = document.createElement('div');
    div.id = 'success-message';
    div.classList.add('success-message');
    div.style.color = 'rgb(128, 183, 65)';

    const p = document.createElement('p');
    p.innerHTML = message;

    div.appendChild(p);

    return div;
}

export const clearErrors = (selectorAll = null, remove = false) => {  
    selectorAll = selectorAll ?? '.tww-form-error';

    if (document.querySelectorAll(selectorAll)) {
        if(remove) {
            document.querySelectorAll(selectorAll).forEach(function(element) {
                element.remove();
            });
        } else {
            document.querySelectorAll(selectorAll).forEach(function(element) {
                element.innerHTML = '';
            });
        }
    }
}

export const clearSuccess = (selectorAll = null) => {    
    selectorAll = selectorAll ?? '.tww-plus-success';
    if(document.querySelectorAll(selectorAll)) {
        document.querySelectorAll(selectorAll).forEach(function(element) {
            element.innerHTML = '';
        });
    }
}

export const validateEmail = (email) => {
    const re = /\S+@\S+\.\S+/;
    return re.test(email);
}

export const initPasswordEye = () => {
    const passwordFields = document.querySelectorAll('.tww-password-wrapper input[type="password"]');

    passwordFields.forEach((field) => {
        const eye = field.closest('.tww-password-wrapper').querySelector('.tww-plus-password-eye');

        eye.addEventListener('click', () => {
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.add('dashicons-visibility');
                eye.classList.remove('dashicons-hidden');
            } else {
                field.type = 'password';
                eye.classList.add('dashicons-hidden');
                eye.classList.remove('dashicons-visibility');
            }
        });
    });
}

(function() {
    initPasswordEye();
})();