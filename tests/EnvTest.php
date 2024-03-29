<?php declare(strict_types=1);

/**
 * This file is part of RESTSpeaker, a PHP Experts, Inc., Project.
 *
 * Copyright © 2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/RESTSpeaker
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\RESTSpeaker\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPExperts\RESTSpeaker\HTTPSpeaker;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testWorks_as_a_Guzzle_proxy()
    {
        $expectedBody = '<html lang="us">Hi</html>';
        $expected = new Response(200, ['Content-Type' => 'text/html'], $expectedBody);
        $this->guzzleHandler->append(
            $expected
        );

        $actual = $this->http->get('https://somewhere.com/');
        self::assertEquals($expected, $actual);
        self::assertEquals($expectedBody, $actual->getBody());
    }

    public function testIdentifiesAsItsOwnUserAgent()
    {
        $this->guzzleHandler->append(
            new Response(200, ['Content-Type' => 'text/html'], '')
        );

        $this->http->get('https://somewhere.com/');
        $requestHeaders = $this->guzzleHandler->getLastRequest()->getHeaders();

        $phpV = phpversion();
        $expected = "PHPExperts/RESTSpeaker-1.0 (PHP {$phpV})";
        self::assertEquals($expected, $requestHeaders['User-Agent'][0]);
    }

    public function testRequestsTextHtmlContentType()
    {
        $this->guzzleHandler->append(
            new Response(200, [], '')
        );

        $this->http->get('https://somewhere.com/');
        $requestHeaders = $this->guzzleHandler->getLastRequest()->getHeaders();

        $expected = 'text/html';
        self::assertEquals($expected, $requestHeaders['Content-Type'][0]);
    }
}
