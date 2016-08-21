<?php

namespace PontusHorn\Tests\Unit;

use \PontusHorn\Github\GithubURL;

class GithubURLTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param string $url
     * @dataProvider constructorFailureProvider
     * @expectedException \PontusHorn\Github\RecoverableException
     */
    public function testConstructorFailure($url) {
        new GithubURL($url);
    }

    /**
     * @param string $url
     * @param string $expectedUsername
     * @dataProvider usernameRetrievalProvider
     */
    public function testUsernameRetrieval($url, $expectedUsername) {
        $this->assertEquals($expectedUsername, (new GithubURL($url))->getUsername());
    }

    /**
     * @param string $url
     * @param int $expectedUserId
     * @dataProvider userIdRetrievalProvider
     */
    public function testUserIdRetrieval($url, $expectedUserId) {
        $this->assertEquals($expectedUserId, (new GithubURL($url))->getUserId());
    }

    public function constructorFailureProvider() {
        return [
            ['https://google.com/imghp'],
            ['https://github.com/Invalid_Username'],
            ['https://help.github.com/articles']
        ];
    }

    public function usernameRetrievalProvider() {
        return [
            ['https://github.com/PontusHorn', 'PontusHorn'],
            ['https://github.com/PontusHorn/', 'PontusHorn'],
            ['https://github.com/PontusHorn/hubr', 'PontusHorn'],
            ['http://github.com/PontusHorn', 'PontusHorn'],
            ['http://github.com/PontusHorn/', 'PontusHorn'],
            ['http://github.com/PontusHorn/hubr', 'PontusHorn'],
            ['github.com/PontusHorn', 'PontusHorn'],
            ['github.com/PontusHorn/', 'PontusHorn'],
            ['github.com/PontusHorn/hubr', 'PontusHorn'],
        ];
    }

    public function userIdRetrievalProvider() {
        return [
            ['https://github.com/PontusHorn', 6806243]
        ];
    }

}
