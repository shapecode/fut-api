<!--
  Title: FIFA 21 WebApp API
  Description: A simply way to manage your FIFA 21 Ultimate Team with a PHP
  -->

# FIFA 21 WebApp API

Manage your FIFA 21 Ultimate Team using this FIFA 21 Ultimate Team API.
Written solely in PHP

[![paypal](https://img.shields.io/badge/Donate-Paypal-blue.svg)](http://paypal.me/nloges)

[![PHP Version](https://img.shields.io/packagist/php-v/shapecode/fut-api.svg)](https://packagist.org/packages/shapecode/fut-api)
[![Latest Stable Version](https://img.shields.io/packagist/v/shapecode/fut-api.svg?label=stable)](https://packagist.org/packages/shapecode/fut-api)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/shapecode/fut-api.svg?label=unstable)](https://packagist.org/packages/shapecode/fut-api)
[![Total Downloads](https://img.shields.io/packagist/dt/shapecode/fut-api.svg)](https://packagist.org/packages/shapecode/fut-api)
[![Monthly Downloads](https://img.shields.io/packagist/dm/shapecode/fut-api.svg?label=monthly)](https://packagist.org/packages/shapecode/fut-api)
[![Daily Downloads](https://img.shields.io/packagist/dd/shapecode/fut-api.svg?label=daily)](https://packagist.org/packages/shapecode/fut-api)
[![License](https://img.shields.io/packagist/l/shapecode/fut-api.svg)](https://packagist.org/packages/shapecode/fut-api)


## Installing

The recommended way to install FIFA 21 WebApp API is through
[Composer](http://getcomposer.org).

```bash
composer require shapecode/fut-api "~20.0@dev"
```

## Contribute

Don't be shy. Feel free to contribute and create pull-requests. There's a lot to do.

## Usage

### Login

Login parameters:

- email: [string] email used for logging into the FIFA 21 WebApp
- password: [string] password used for logging into the FIFA 21 WebApp
- platform: [string] pc/ps4/ps4/xbox/xbox360
- code: [string] email code for two-step verification (make sure to use string if your code starts with 0).

```php
use Shapecode\FUT\Client\Api\Core;
use Shapecode\FUT\Client\Authentication\Credentials;
use Shapecode\FUT\Client\Authentication\Account;
use Shapecode\FUT\Client\Authentication\Session;

$credentials = new Credentials($email, $password, $platform);

// if you already have a valid session
$session = new Session($persona, $nucleus, $phishing, $session, $dob, $accessToken, $tokenType);

// otherwise
$session = null;

$account = new Account($credentials, $session);
$fut = new Core($account);

try {
    $login = $fut->login($code);
    $session = $account->getSession();
} catch(Exception $e) {
    $reason = $e->getMessage();
    die("We have an error logging in: ".$reason);
}
```

After you have initiated your first session, you can then use the API wthout logging in again using the session info.

    
### Search

Optional parameters:
- micr: [int] Minimal price.
- macr: [int] Maximum price.
- minb: [int] Minimal buy now price.
- maxb: [int] Maximum buy now price.
- lev: ['bronze'/'silver'/gold'] Card level.
- start: [int] Start page number.
- type: ['fitness'/'player'/'?'] Card category.
- maskedDefId: [int] Player id.
- defId: [int] defId.
- leag: [int] League id.
- team: [int] Club id.
- pos: [str] Position. (e.g. "ST")
- zone: ['attacker'/'?'] zone.
- nat: [int] Nation id.
- rare: [boolean] True for searching special cards.
- playStyle: [str?] playStyle.
- num: [int] Amount of cards on single page (changing this might be risky).

```php
$options = [];
$items = $fut->search($options);
```
    
### Logout

Replicates clicking the Logout button.

```php
$fut->logout();
```


## License

GNU GPLv3

##### Forked

https://github.com/InkedCurtis/FUT-API
