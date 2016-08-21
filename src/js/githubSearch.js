'use strict';

import '../../node_modules/whatwg-fetch/fetch';
import apiClient from './apiClient';
import {getCurrentError, showError, hideError} from './error';

export default function () {
    document.getElementById('githubSearchForm').addEventListener('submit', interceptSubmit);

    history.replaceState(getPageStateFromHtml(), '');
    window.addEventListener('popstate', function (event) {
        setPageState(event.state);
    });
};

function interceptSubmit(event) {
    var submitButton = document.getElementById('githubSearchButton');
    var url = getURLField().value;

    event.preventDefault();

    hideError();
    submitButton.classList.add('BigOldButton-progress');
    submitButton.disabled = true;

    apiClient.get('/url/' + url)
        .then(function (data) {
            setPageState(data);
            history.pushState(data, '', '/url/' + data.url);
        })
        .catch(function (error) {
            var state = {
                error: 'An error occurred while working on your request. Sorry about that!',
                url: url
            };

            setPageState(state);
            history.pushState(state, '', '/url/' + url);

            console.error(error);
        })
        .then(function () {
            submitButton.classList.remove('BigOldButton-progress');
            submitButton.disabled = false;
        });
}

function getURLField() { return document.getElementById('githubURLField'); }
function getResultViewElement() { return document.getElementById('resultView'); }
function getResultViewURLElement() { return document.getElementById('resultViewURL'); }
function getResultViewUserIdElement() { return document.getElementById('resultViewUserId'); }
function getResultViewUsernameElement() { return document.getElementById('resultViewUsername'); }

function getPageStateFromHtml() {
    return {
        error: getCurrentError(),
        url: getResultViewURLElement().textContent,
        user: {
            id: getResultViewUserIdElement().textContent,
            username: getResultViewUsernameElement().textContent,
        }
    };
}

function setPageState(state) {
    if (state.error) {
        getResultViewElement().hidden = true;
        showError(state.error);
        return;
    }

    hideError();
    getURLField().value = getResultViewURLElement().textContent = state.url;
    getResultViewURLElement().href = 'https://' + state.url;
    getResultViewUserIdElement().textContent = state.user.id;
    getResultViewUsernameElement().textContent = state.user.username;
    getResultViewElement().hidden = false;
}
