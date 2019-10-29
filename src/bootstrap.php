<?php

declare(strict_types=1);

if (! function_exists('msleep')) {
    function msleep(int $milliseconds) : void
    {
        usleep($milliseconds * 1000);
    }
}
