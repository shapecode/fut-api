<?php

namespace Shapecode\FUT\Client\Util;

use Shapecode\FUT\Client\Exception\FutException;

/**
 * Class FutUtil
 *
 * @package Shapecode\FUT\Client\Util
 * @author  Shapecode
 */
class FutUtil
{

    /**
     */
    private function __construct()
    {
        // object not allowed
    }

    /**
     * @param $assetId
     *
     * @return int
     */
    public static function getBaseId($assetId)
    {
        $version = 0;
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

    /**
     * @param $platform
     *
     * @return string
     * @throws FutException
     */
    public static function getGameSku($platform)
    {
        switch ($platform) {
            case 'pc':
                return 'FFA20PCC';
                break;
            case 'xbox':
                return 'FFA20XBO';
                break;
            case 'xbox360':
                return 'FFA20XBX';
                break;
            case 'ps3':
                return 'FFA20PS3';
                break;
            case 'ps4':
                return 'FFA20PS4';
                break;
            default:
                throw new FutException('Wrong platform. (Valid ones are pc/xbox/xbox360/ps3/ps4)');
                break;
        }
    }
}
