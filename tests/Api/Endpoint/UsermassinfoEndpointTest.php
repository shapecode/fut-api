<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Api\Endpoint;

use Shapecode\FUT\Client\Items\Config;

class UsermassinfoEndpointTest extends EndpointTestCase
{
    public function testSearch() : void
    {
        $factory = $this->createClientFactoryMock('usermassinfo.response.json');
        $core    = $this->createCore($factory);

        $result = $core->usermassInfo();

        self::assertCount(124, $result->getSettings()->getConfigs());
        self::assertTrue($result->isHighTierReturningUser());
        self::assertFalse($result->isPlayerPicksTemporaryStorageNotEmpty());
        self::assertNotEmpty($result->getRawBody());
        self::assertContainsOnlyInstancesOf(Config::class, $result->getSettings()->getConfigs());
    }
}
