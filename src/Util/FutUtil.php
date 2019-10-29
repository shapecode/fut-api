<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Util;

use Shapecode\FUT\Client\Exception\FutException;

class FutUtil
{
    private const GAME_SKU = [
        'pc'      => 'FFA20PCC',
        'xbox'    => 'FFA20XBO',
        'xbox360' => 'FFA20XBX',
        'ps3'     => 'FFA20PS3',
        'ps4'     => 'FFA20PS4',
    ];

    private function __construct()
    {
        // object not allowed
    }

    public static function getBaseId(int $assetId) : int
    {
        $version  = 0;
        $assetId += 0xC4000000;
        while ($assetId > 0x01000000) {
            $version++;
            if ($version === 1) {
                //the constant applied to all items
                $assetId -= 1342177280;
            } elseif ($version === 2) {
                //the value added to the first updated version
                $assetId -= 50331648;
            } else {
                //the value added on all subsequent versions
                $assetId -= 16777216;
            }
        }

        return $assetId;
    }

    public static function getGameSku(string $platform) : string
    {
        if (! isset(self::GAME_SKU[$platform])) {
            throw new FutException('Wrong platform. (Valid ones are pc/xbox/xbox360/ps3/ps4)');
        }

        return self::GAME_SKU[$platform];
    }
}
