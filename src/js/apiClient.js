'use strict';

var defaultFetchOptions = {
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
};

export default {
    get: function (url) {
        return fetch(url, defaultFetchOptions)
            .then(function (response) {
                return response.json();
            });
    }
};
