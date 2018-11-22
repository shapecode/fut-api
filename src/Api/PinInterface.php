<?php

namespace Shapecode\FUT\Api;

/**
 * Interface PinInterface
 *
 * @package Shapecode\FUT\Api
 * @author  Shapecode
 */
interface PinInterface
{

    const PIN_URL = 'https://pin-river.data.ea.com/pinEvents';

    /**
     * @param      $en
     * @param bool $pgid
     * @param bool $status
     * @param bool $source
     * @param bool $end_reason
     */
    public function sendEvent($en, $pgid = false, $status = false, $source = false, $end_reason = false);

    /**
     * @param      $en
     * @param bool $pgid
     * @param bool $status
     * @param bool $source
     * @param bool $end_reason
     *
     * @return array
     */
    public function event($en, $pgid = false, $status = false, $source = false, $end_reason = false);

    /**
     * @param $events
     *
     * @return bool
     */
    public function send($events);
}
