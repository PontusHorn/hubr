<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Negotiation\Negotiator;
use \PontusHorn\Github\GithubURL;

/**
 * Start page
 */
$app->get('/', function (Request $request, Response $response, $args) {
    return $this->view->render($response, 'index.twig', $args);
});

/**
 * URL form submission not intercepted by JS, e.g.:
 * /url/?url=https%3A%2F%2Fgithub.com%2Ffoo
 * /url/?url=https%3A%2F%2Fgithub.com%2Ffoo%2Fbar
 */
$app->get('/url/', function (Request $request, Response $response, $args) {
    $url = $request->getQueryParam('url');

    if (!$url) {
        return $this->view->render($response, '404.twig')->withStatus(404);
    }

    try {
        $githubURL = new GithubURL($url);
    } catch (\PontusHorn\Github\RecoverableException $e) {
        return $this->view->render($response, 'index.twig', [
            'url'   => $url,
            'error' => $e->getMessage()
        ]);
    }

    // Redirect to canonical URL
    return $response->withStatus(302)->withHeader('Location', '/url/' . $githubURL->getId());
});

/**
 * Standard URL page, e.g.:
 * /url/github.com/foo
 * /url/github.com/foo/bar
 */
$app->get('/url/{url:.+}', function (Request $request, Response $response, $args) {
    try {
        $githubURL = new GithubURL($args['url']);
        $data = [
            'url'  => $args['url'],
            'user' => [
                'id'       => $githubURL->getUserId(),
                'username' => $githubURL->getUsername()
            ]
        ];
    } catch (\PontusHorn\Github\RecoverableException $e) {
        $data = [
            'url'   => $args['url'],
            'error' => $e->getMessage()
        ];
    }

    return (getResponseMediaType($request) === 'application/json') ? $response->withJson($data)
        : $this->view->render($response, 'index.twig', $data);
})->setName('url');

/**
 * Helper function to determine whether or not to respond with
 * @param Request $request
 * @return bool
 */
function getResponseMediaType(Request $request) {
    $negotiator = new Negotiator();
    $mediaType = $negotiator->getBest($request->getHeaderLine('Accept'), ['application/json', 'text/html']);

    return $mediaType->getValue();
}
