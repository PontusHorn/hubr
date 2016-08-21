'use strict';

import initGithubSearch from './githubSearch';

function initApplication() {
    initGithubSearch();
}

if (document.readyState !== 'loading') {
    initApplication();
} else {
    document.addEventListener('DOMContentLoaded', initApplication);
}