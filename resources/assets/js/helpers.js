const getEl = (el) =>  document.getElementById(el);
const getAll = (className) => document.querySelectorAll('.'  + className);

const errorDiv = (message) => {
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

const successDiv = (message) => {
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

const clearErrors = (selectorAll = null) => {  
    selectorAll = selectorAll ?? '.tww-form-error';
    if(document.querySelectorAll(selectorAll)) {
        document.querySelectorAll(selectorAll).forEach(function(element) {
            element.innerHTML = '';
        });
    }
}

const clearSuccess = (selectorAll = null) => {    
    selectorAll = selectorAll ?? '.tww-plus-success';
    if(document.querySelectorAll(selectorAll)) {
        document.querySelectorAll(selectorAll).forEach(function(element) {
            element.innerHTML = '';
        });
    }
}