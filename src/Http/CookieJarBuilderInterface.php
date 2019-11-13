<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use GuzzleHttp\Cookie\CookieJarInterface;
use Shapecode\FUT\Client\Authentication\AccountInterface;

interface CookieJarBuilderInterface
{
    public function createCookieJar(AccountInterface $account) : CookieJarInterface;
}
