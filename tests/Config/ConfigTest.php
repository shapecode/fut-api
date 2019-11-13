<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Config;

use PHPStan\Testing\TestCase;
use Shapecode\FUT\Client\Config\Config;

class ConfigTest extends TestCase
{
    public function testCreation() : void
    {
        $config = new Config();

        $options = $config->getOptions();

        $keys = [
            'requestProtocol',
            'authURL',
            'eadpConnectHost',
            'eadpPortalHost',
            'eadpProxyHost',
            'eadpClientId',
            'eadpClientSecret',
            'pinURL',
            'releaseType',
            'showOffURL',
            'resourceRoot',
            'resourceBase',
            'changelist',
            'requestTimeout',
            'localStorageVersion',
            'maxConsecutive500Errors',
            'settingsRefreshInterval',
            'verboseLogging',
            'staticResponseData',
            'originCss',
            'originJS',
            'originHost',
            'originProfile',
            'originMasterTitle',
            'funCaptchaPublicKey',
            'userAgent',
            'delay',
            'delayMinTime',
            'delayMaxTime',
        ];

        self::assertCount(29, $options);

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $options);
        }
    }
}
