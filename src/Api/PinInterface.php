<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Api;

/**
 * Interface PinInterface
 */
interface PinInterface
{
    public const PIN_URL = 'https://pin-river.data.ea.com/pinEvents';

    public function sendEvent(string $en, ?string $pgid = null, ?string $status = null, ?string $source = null, ?string $end_reason = null) : void;

    /**
     * @return mixed[]
     */
    public function event(string $en, ?string $pgid = null, ?string $status = null, ?string $source = null, ?string $end_reason = null) : array;

    /**
     * @param mixed[] $events
     */
    public function send(array $events) : bool;
}
