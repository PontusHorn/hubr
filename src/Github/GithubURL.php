<?php

namespace PontusHorn\Github;

class GithubURL {

    private $url;
    private $userId;

    public function __construct($url) {
        if (!GithubURL::isValidURL($url)) {
            throw new RecoverableException('That seems to be an invalid or unsupported URL. '
                . 'Try e.g. "github.com/foo" or "github.com/foo/bar".');
        }

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getId() {
        return preg_replace('~^https?://~', '', $this->url) ?: '';
    }

    /**
     * @return string
     * @throws RecoverableException
     */
    public function getUserId() {
        if (isset($this->userId)) {
            return $this->userId;
        }

        $userData = GithubAPI::getUserData($this->getUsername());

        if (!$userData['id']) {
            throw new RecoverableException('Could not find a user for the URL "' . $this->url . '".');
        }

        $this->userId = $userData['id'];

        return $userData['id'];
    }

    /**
     * @return string
     */
    public function getUsername() {
        return preg_replace('~^(https?://)?github.com/([a-zA-Z\-]+)(/.*)?$~', '$2', $this->url);
    }

    /**
     * @param $url
     * @return bool
     */
    private static function isValidURL($url) {
        return (bool)preg_match('~^(https?://)?github.com/([a-zA-Z\-]+)(/.*)?$~', $url);
    }

}