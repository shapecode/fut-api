<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

/**
 * Interface PinInterface
 */
interface PinInterface
{
    public const PIN_URL = 'https://pin-river.data.ea.com/pinEvents';

    /**
     * @param mixed $en
     */
    public function sendEvent($en, bool $pgid = false, bool $status = false, bool $source = false, bool $end_reason = false) : void;

    /**
     * @param mixed $en
     *
     * @return mixed[]
     */
    public function event($en, bool $pgid = false, bool $status = false, bool $source = false, bool $end_reason = false) : array;

    /**
     * @param mixed[] $events
     */
    public function send(array $events) : bool;
}
