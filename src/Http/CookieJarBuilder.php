<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Http;

use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\FileCookieJar;
use Shapecode\FUT\Client\Authentication\AccountInterface;
use function array_key_exists;
use function sha1;
use function sys_get_temp_dir;

class CookieJarBuilder implements CookieJarBuilderInterface
{
    /** @var CookieJarInterface[] */
    protected $jars = [];

    public function createCookieJar(AccountInterface $account) : CookieJarInterface
    {
        $email = $account->getCredentials()->getEmail();

        if (array_key_exists($email, $this->jars) === false) {
            $this->jars[$email] = $this->createFileCookieJarByTemp($account->getCredentials()->getEmail());
        }

        return $this->jars[$email];
    }

    private function createFileCookieJarByTemp(string $email) : CookieJarInterface
    {
        $filename = sys_get_temp_dir() . '/' . sha1($email);

        return $this->createFileCookieJarByFilename($filename);
    }

    private function createFileCookieJarByFilename(string $filename) : CookieJarInterface
    {
        return new FileCookieJar($filename, true);
    }
}
