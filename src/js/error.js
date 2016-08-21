'use strict';

export function showError(message) {
    var errorMessageElement = getErrorMessageElement();

    errorMessageElement.textContent = message;
    errorMessageElement.hidden = false;
}

export function hideError() {
    getErrorMessageElement().hidden = true;
}

export function getCurrentError() {
    return getErrorMessageElement().textContent;
}

function getErrorMessageElement() {
    return document.getElementById('errorMessage');
}