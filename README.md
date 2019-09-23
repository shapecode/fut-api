<!--
  Title: FIFA 20 WebApp API
  Description: A simply way to manage your FIFA 20 Ultimate Team with a PHP
  -->

FIFA 20 WebApp API
=============

Manage your FIFA 20 Ultimate Team using this FIFA 20 Ultimate Team API.
Written solely in PHP

Installing FUTApi
=======

The recommended way to install FIFA 20 WebApp API is through
[Composer](http://getcomposer.org).

```bash
composer require nicklog/fut-api
```

Usage
=====

Login
-----

Login parameters:

- email: [string] email used for logging into the FIFA 20 WebApp
- password: [string] password used for logging into the FIFA 20 WebApp
- platform: [string] pc/ps4/ps4/xbox/xbox360
- code: [string] email/sms code for two-step verification (make sure to use string if your code starts with 0).

```php
use FUTApi\Api\Core;
use FUTApi\Exception\FutError;
use FUTApi\Api\Authentication\Account;
use FUTApi\Api\Authentication\Credentials;
use FUTApi\Api\Authentication\Session;

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
} catch(FutError $e) {
    $reason = $e->getReason();
    die("We have an error logging in: ".$reason);
}
```

After you have initiated your first session, you can then use the API wthout logging in again using the session info.

    
Search
------

Optional parameters:

- min_price: [int] Minimal price.
- max_price: [int] Maximum price.
- min_buy: [int] Minimal buy now price.
- max_buy: [int] Maximum buy now price.
- level: ['bronze'/'silver'/gold'] Card level.
- start: [int] Start page number.
- category: ['fitness'/'?'] Card category.
- assetId: [int] assetId.
- defId: [int] defId.
- league: [int] League id.
- club: [int] Club id.
- position: [int?/str?] Position.
- zone: ['attacker'/'?'] zone.
- nationality: [int] Nation id.
- rare: [boolean] True for searching special cards.
- playStyle: [str?] playStyle.
- page_size: [int] Amount of cards on single page (changing this might be risky).

```php
$options = [];
$items = $fut->search($options);
```
    
Logout
------

Replicates clicking the Logout button.

```php
$fut->logout();
```


License
-------

GNU GPLv3

Forked from
-------

https://github.com/InkedCurtis/FUT-API
