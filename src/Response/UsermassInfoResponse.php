<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Response;

use Shapecode\FUT\Client\Items\Settings;

class UsermassInfoResponse extends AbstractResponse
{
    /** @var Settings */
    private $settings;
    private $userInfo;
    private $purchasedItems;
    private $loanPlayerClientData;
    private $squad;
    private $clubUser;
    private $activeMessages;
    private $onboardingClientData;

    /** @var bool */
    private $highTierReturningUser;

    /** @var bool */
    private $playerPicksTemporaryStorageNotEmpty;

    public function __construct(
        array $rawBody,
        Settings $settings,
        bool $highTierReturningUser,
        bool $playerPicksTemporaryStorageNotEmpty
    ) {
        parent::__construct($rawBody);

        $this->settings                            = $settings;
        $this->highTierReturningUser               = $highTierReturningUser;
        $this->playerPicksTemporaryStorageNotEmpty = $playerPicksTemporaryStorageNotEmpty;
    }

    public function getSettings() : Settings
    {
        return $this->settings;
    }

    public function isHighTierReturningUser() : bool
    {
        return $this->highTierReturningUser;
    }

    public function isPlayerPicksTemporaryStorageNotEmpty() : bool
    {
        return $this->playerPicksTemporaryStorageNotEmpty;
    }
}
