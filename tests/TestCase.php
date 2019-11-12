<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Tests;

use Symfony\Component\Dotenv\Dotenv;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../.env');
    }
}
