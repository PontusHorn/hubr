<?php

namespace PontusHorn\Github;

use \Requests;

class GithubAPI {

    const SUCCESS = 0;
    const ERROR_GENERIC = 1;
    const ERROR_REQUEST_FAILED = 2;
    const ERROR_INVALID_JSON = 3;
    const ERROR_RATE_LIMIT_EXCEEDED = 4;

    private static $standard_request_headers = ['Accept' => 'application/json'];

    /**
     * @param string $username
     * @return array
     * @throws RecoverableException
     */
    public static function getUserData($username) {
        try {
            $response = GithubAPI::getResponseBody('https://api.github.com/users/' . $username);
        } catch (RecoverableException $e) {
            // Expose "rate limit exceeded" error
            if ($e->getCode() === GithubAPI::ERROR_RATE_LIMIT_EXCEEDED) {
                throw $e;
            }

            // Hide other errors behind a more generic error
            throw new RecoverableException('Failed to fetch user data from Github API.',
                GithubAPI::ERROR_GENERIC, $e);
        }

        if ($response['message'] === 'Not Found') {
            return [];
        }

        return $response;
    }

    /**
     * @param string $url
     * @return array
     * @throws RecoverableException
     */
    private static function getResponseBody($url) {
        try {
            $response = Requests::get($url, GithubAPI::$standard_request_headers);
        } catch (\Exception $e) {
            throw new RecoverableException('API call to fetch Github user data failed.',
                GithubAPI::ERROR_REQUEST_FAILED, $e);
        }

        if (isset($response->headers['x-ratelimit-remaining']) && $response->headers['x-ratelimit-remaining'] == '0') {
            throw new RecoverableException('Github API rate limit exceeded.', GithubAPI::ERROR_RATE_LIMIT_EXCEEDED);
        }

        $data = json_decode($response->body, true);
        if (!is_array($data)) {
            throw new RecoverableException('Failed to decode JSON response from Github API call.',
                GithubAPI::ERROR_INVALID_JSON);
        }

        return $data;
    }

}
	