<?php

if (!function_exists('msleep')) {
    function msleep($milliseconds)
    {
        usleep($milliseconds * 1000);
    }
}
