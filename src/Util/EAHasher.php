<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Util;

use function count;
use function hexdec;
use function ord;
use function strlen;

class EAHasher
{
    /** @var EAHasher */
    private static $instance;

    /** @var int[] */
    public static $R1Shifts = [7, 12, 17, 22, 7, 12, 17, 22, 7, 12, 17, 22, 7, 12, 17, 22];

    /** @var int[] */
    public static $R2Shifts = [5, 9, 14, 20, 5, 9, 14, 20, 5, 9, 14, 20, 5, 9, 14, 20];

    /** @var int[] */
    public static $R3Shifts = [4, 11, 16, 23, 4, 11, 16, 23, 4, 11, 16, 23, 4, 11, 16, 23];

    /** @var int[] */
    public static $R4Shifts = [6, 10, 15, 21, 6, 10, 15, 21, 6, 10, 15, 21, 6, 10, 15, 21];

    /** @var string */
    public static $HexCharacters = '0123456789abcdef';

    public static function getInstance() : EAHasher
    {
        if (static::$instance === null) {
            $class            = self::class;
            static::$instance = new $class();
        }

        return static::$instance;
    }

    public function getHash(string $string) : ?string
    {
        return $this->hash($string);
    }

    private function int32(int $val) : int
    {
        return $val & 0xFFFFFFFF;
    }

    private function ff(int $a, int $b, int $c, int $d, int $x, int $s, int $t) : int
    {
        $a = $this->int32($a);
        $b = $this->int32($b);
        $c = $this->int32($c);
        $d = $this->int32($d);
        $x = $this->int32($x);
        $s = $this->int32($s);
        $t = $this->int32($t);

        return $this->cmn(($b & $c) | ((~$b) & $d), $a, $b, $x, $s, $t);
    }

    private function gg(int $a, int $b, int $c, int $d, int $x, int $s, int $t) : int
    {
        $a = $this->int32($a);
        $b = $this->int32($b);
        $c = $this->int32($c);
        $d = $this->int32($d);
        $x = $this->int32($x);
        $s = $this->int32($s);
        $t = $this->int32($t);

        return $this->cmn(($b & $d) | ($c & (~$d)), $a, $b, $x, $s, $t);
    }

    private function hh(int $a, int $b, int $c, int $d, int $x, int $s, int $t) : int
    {
        $a = $this->int32($a);
        $b = $this->int32($b);
        $c = $this->int32($c);
        $d = $this->int32($d);
        $x = $this->int32($x);
        $s = $this->int32($s);
        $t = $this->int32($t);

        return $this->cmn($b ^ $c ^ $d, $a, $b, $x, $s, $t);
    }

    private function ii(int $a, int $b, int $c, int $d, int $x, int $s, int $t) : int
    {
        $a = $this->int32($a);
        $b = $this->int32($b);
        $c = $this->int32($c);
        $d = $this->int32($d);
        $x = $this->int32($x);
        $s = $this->int32($s);
        $t = $this->int32($t);

        return $this->cmn($c ^ ($b | (~$d)), $a, $b, $x, $s, $t);
    }

    private function cmn(int $q, int $a, int $b, int $x, int $s, int $t) : int
    {
        $q = $this->int32($q);
        $b = $this->int32($b);
        $x = $this->int32($x);
        $s = $this->int32($s);
        $t = $this->int32($t);

        return $this->add($this->bitwiseRotate($this->add($this->add($a, $q), $this->add($x, $t)), $s), $b);
    }

    private function add(int $x, int $y) : int
    {
        $x   = $this->int32($x);
        $y   = $this->int32($y);
        $lsw = ($x & 0xFFFF) + ($y & 0xFFFF);
        $msw = ($x >> 16) + ($y >> 16) + ($lsw >> 16);

        return ($msw << 16) | ($lsw & 0xFFFF);
    }

    private function bitwiseRotate(int $x, int $c) : int
    {
        $x = $this->int32($x);

        return ($x << $c) | $this->uRShift($x, 32 - $c);
    }

    private function uRShift(int $number, int $shiftBits) : int
    {
        $number = $this->int32($number);
        $z      = hexdec('80000000');
//        if ($z !== null & $number !== null) {
            $number   = ($number >> 1);
            $number  &= (~$z);
            $number  |= 0x40000000;
            $number >>= ($shiftBits - 1);
//        } else {
//            $number = ($number >> $shiftBits);
//        }

        return $number;
    }

    private function numberToHex(int $number) : string
    {
        $result = '';
        for ($j = 0; $j <= 3; $j++) {
            $result .= static::$HexCharacters[($number >> ($j * 8 + 4)) & 0x0F];
            $result .= static::$HexCharacters[($number >> ($j * 8)) & 0x0F];
        }

        return $result;
    }

    /**
     * @return int[]
     */
    private function chunkInput(string $input) : array
    {
        $inputLength    = strlen($input);
        $numberOfBlocks = $inputLength + 8 >> 6 + 1;
        $blocks         = [];
        for ($i = 0; $i < $numberOfBlocks * 16; $i++) {
            $blocks[$i] = 0;
        }
        for ($i = 0; $i < $inputLength; $i++) {
            $blocks[$i >> 2] |= ord($input[$i]) << (($i % 4) * 8);
        }
        $blocks[$inputLength >> 2]       |= 0x80 << (($inputLength % 4) * 8);
        $blocks[$numberOfBlocks * 16 - 2] = $inputLength * 8;

        return $blocks;
    }

    private function hash(string $string) : string
    {
        $chunks = $this->chunkInput($string);
        $count  = count($chunks);
        $a      = 1732584193;
        $b      = -271733879;
        $c      = -1732584194;
        $d      = 271733878;
        for ($i = 0; $i < $count; $i += 16) {
            $tempA = $a;
            $tempB = $b;
            $tempC = $c;
            $tempD = $d;
            $a     = $this->ff($a, $b, $c, $d, $chunks[$i + 0], static::$R1Shifts[0], -680876936);
            $d     = $this->ff($d, $a, $b, $c, $chunks[$i + 1], static::$R1Shifts[1], -389564586);
            $c     = $this->ff($c, $d, $a, $b, $chunks[$i + 2], static::$R1Shifts[2], 606105819);
            $b     = $this->ff($b, $c, $d, $a, $chunks[$i + 3], static::$R1Shifts[3], -1044525330);
            $a     = $this->ff($a, $b, $c, $d, $chunks[$i + 4], static::$R1Shifts[4], -176418897);
            $d     = $this->ff($d, $a, $b, $c, $chunks[$i + 5], static::$R1Shifts[5], 1200080426);
            $c     = $this->ff($c, $d, $a, $b, $chunks[$i + 6], static::$R1Shifts[6], -1473231341);
            $b     = $this->ff($b, $c, $d, $a, $chunks[$i + 7], static::$R1Shifts[7], -45705983);
            $a     = $this->ff($a, $b, $c, $d, $chunks[$i + 8], static::$R1Shifts[8], 1770035416);
            $d     = $this->ff($d, $a, $b, $c, $chunks[$i + 9], static::$R1Shifts[9], -1958414417);
            $c     = $this->ff($c, $d, $a, $b, $chunks[$i + 10], static::$R1Shifts[10], -42063);
            $b     = $this->ff($b, $c, $d, $a, $chunks[$i + 11], static::$R1Shifts[11], -1990404162);
            $a     = $this->ff($a, $b, $c, $d, $chunks[$i + 12], static::$R1Shifts[12], 1804603682);
            $d     = $this->ff($d, $a, $b, $c, $chunks[$i + 13], static::$R1Shifts[13], -40341101);
            $c     = $this->ff($c, $d, $a, $b, $chunks[$i + 14], static::$R1Shifts[14], -1502002290);
            $b     = $this->ff($b, $c, $d, $a, $chunks[$i + 15], static::$R1Shifts[15], 1236535329);
            $a     = $this->gg($a, $b, $c, $d, $chunks[$i + 1], static::$R2Shifts[0], -165796510);
            $d     = $this->gg($d, $a, $b, $c, $chunks[$i + 6], static::$R2Shifts[1], -1069501632);
            $c     = $this->gg($c, $d, $a, $b, $chunks[$i + 11], static::$R2Shifts[2], 643717713);
            $b     = $this->gg($b, $c, $d, $a, $chunks[$i + 0], static::$R2Shifts[3], -373897302);
            $a     = $this->gg($a, $b, $c, $d, $chunks[$i + 5], static::$R2Shifts[4], -701558691);
            $d     = $this->gg($d, $a, $b, $c, $chunks[$i + 10], static::$R2Shifts[5], 38016083);
            $c     = $this->gg($c, $d, $a, $b, $chunks[$i + 15], static::$R2Shifts[6], -660478335);
            $b     = $this->gg($b, $c, $d, $a, $chunks[$i + 4], static::$R2Shifts[7], -405537848);
            $a     = $this->gg($a, $b, $c, $d, $chunks[$i + 9], static::$R2Shifts[8], 568446438);
            $d     = $this->gg($d, $a, $b, $c, $chunks[$i + 14], static::$R2Shifts[9], -1019803690);
            $c     = $this->gg($c, $d, $a, $b, $chunks[$i + 3], static::$R2Shifts[10], -187363961);
            $b     = $this->gg($b, $c, $d, $a, $chunks[$i + 8], static::$R2Shifts[11], 1163531501);
            $a     = $this->gg($a, $b, $c, $d, $chunks[$i + 13], static::$R2Shifts[12], -1444681467);
            $d     = $this->gg($d, $a, $b, $c, $chunks[$i + 2], static::$R2Shifts[13], -51403784);
            $c     = $this->gg($c, $d, $a, $b, $chunks[$i + 7], static::$R2Shifts[14], 1735328473);
            $b     = $this->gg($b, $c, $d, $a, $chunks[$i + 12], static::$R2Shifts[15], -1926607734);
            $a     = $this->hh($a, $b, $c, $d, $chunks[$i + 5], static::$R3Shifts[0], -378558);
            $d     = $this->hh($d, $a, $b, $c, $chunks[$i + 8], static::$R3Shifts[1], -2022574463);
            //line below uses _r2Shifts[2] where as MD5 would use _r3Shifts[2]
            $c = $this->hh($c, $d, $a, $b, $chunks[$i + 11], static::$R2Shifts[2], 1839030562);
            $b = $this->hh($b, $c, $d, $a, $chunks[$i + 14], static::$R3Shifts[3], -35309556);
            $a = $this->hh($a, $b, $c, $d, $chunks[$i + 1], static::$R3Shifts[4], -1530992060);
            $d = $this->hh($d, $a, $b, $c, $chunks[$i + 4], static::$R3Shifts[5], 1272893353);
            $c = $this->hh($c, $d, $a, $b, $chunks[$i + 7], static::$R3Shifts[6], -155497632);
            $b = $this->hh($b, $c, $d, $a, $chunks[$i + 10], static::$R3Shifts[7], -1094730640);
            $a = $this->hh($a, $b, $c, $d, $chunks[$i + 13], static::$R3Shifts[8], 681279174);
            $d = $this->hh($d, $a, $b, $c, $chunks[$i + 0], static::$R3Shifts[9], -358537222);
            $c = $this->hh($c, $d, $a, $b, $chunks[$i + 3], static::$R3Shifts[10], -722521979);
            $b = $this->hh($b, $c, $d, $a, $chunks[$i + 6], static::$R3Shifts[11], 76029189);
            $a = $this->hh($a, $b, $c, $d, $chunks[$i + 9], static::$R3Shifts[12], -640364487);
            $d = $this->hh($d, $a, $b, $c, $chunks[$i + 12], static::$R3Shifts[13], -421815835);
            $c = $this->hh($c, $d, $a, $b, $chunks[$i + 15], static::$R3Shifts[14], 530742520);
            $b = $this->hh($b, $c, $d, $a, $chunks[$i + 2], static::$R3Shifts[15], -995338651);
            $a = $this->ii($a, $b, $c, $d, $chunks[$i + 0], static::$R4Shifts[0], -198630844);
            $d = $this->ii($d, $a, $b, $c, $chunks[$i + 7], static::$R4Shifts[1], 1126891415);
            $c = $this->ii($c, $d, $a, $b, $chunks[$i + 14], static::$R4Shifts[2], -1416354905);
            $b = $this->ii($b, $c, $d, $a, $chunks[$i + 5], static::$R4Shifts[3], -57434055);
            $a = $this->ii($a, $b, $c, $d, $chunks[$i + 12], static::$R4Shifts[4], 1700485571);
            $d = $this->ii($d, $a, $b, $c, $chunks[$i + 3], static::$R4Shifts[5], -1894986606);
            $c = $this->ii($c, $d, $a, $b, $chunks[$i + 10], static::$R4Shifts[6], -1051523);
            $b = $this->ii($b, $c, $d, $a, $chunks[$i + 1], static::$R4Shifts[7], -2054922799);
            $a = $this->ii($a, $b, $c, $d, $chunks[$i + 8], static::$R4Shifts[8], 1873313359);
            $d = $this->ii($d, $a, $b, $c, $chunks[$i + 15], static::$R4Shifts[9], -30611744);
            $c = $this->ii($c, $d, $a, $b, $chunks[$i + 6], static::$R4Shifts[10], -1560198380);
            $b = $this->ii($b, $c, $d, $a, $chunks[$i + 13], static::$R4Shifts[11], 1309151649);
            $a = $this->ii($a, $b, $c, $d, $chunks[$i + 4], static::$R4Shifts[12], -145523070);
            $d = $this->ii($d, $a, $b, $c, $chunks[$i + 11], static::$R4Shifts[13], -1120210379);
            $c = $this->ii($c, $d, $a, $b, $chunks[$i + 2], static::$R4Shifts[14], 718787259);
            $b = $this->ii($b, $c, $d, $a, $chunks[$i + 9], static::$R4Shifts[15], -343485551);
            //This line is doubled for some reason, line below is not in the MD5 version
            $b = $this->ii($b, $c, $d, $a, $chunks[$i + 9], static::$R4Shifts[15], -343485551);
            $a = $this->add($a, $tempA);
            $b = $this->add($b, $tempB);
            $c = $this->add($c, $tempC);
            $d = $this->add($d, $tempD);
        }

        return $this->numberToHex($a) . $this->numberToHex($b) . $this->numberToHex($c) . $this->numberToHex($d);
    }
}
