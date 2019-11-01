<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests\Api\Endpoint;

use Shapecode\FUT\Client\Items\TradeItem;

class TransfermarketEndpointTest extends EndpointTestCase
{
    public function testSearch() : void
    {
        $factory = $this->createClientFactoryMock('transfermarekt.response.json');
        $core    = $this->createCore($factory);

        $result = $core->search();

        self::assertCount(21, $result->getAuctions());
        self::assertCount(0, $result->getBidTokens());
        self::assertNotEmpty($result->getRawBody());
        self::assertContainsOnlyInstancesOf(TradeItem::class, $result->getAuctions());
    }
}
