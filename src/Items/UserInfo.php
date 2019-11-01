<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class UserInfo extends SuperBase
{
    public function getPersonaId() : ?int
    {
        return $this->get('personaId');
    }

    public function getClubName() : ?string
    {
        return $this->get('clubName');
    }

    public function getClubAbbr() : ?string
    {
        return $this->get('clubAbbr');
    }

    public function getDraw() : ?int
    {
        return $this->get('draw');
    }

    public function getLoss() : ?int
    {
        return $this->get('loss');
    }

    public function getCredits() : ?int
    {
        return $this->get('credits');
    }

    public function getBidTokens() : ?array
    {
        return $this->get('bidTokens');
    }

    public function getCurrencies() : ?int
    {
        return $this->get('currencies');
    }

    public function getTrophies() : ?int
    {
        return $this->get('trophies');
    }

    public function getWon() : ?int
    {
        return $this->get('won');
    }

    public function getActives() : ?array
    {
        return $this->get('actives');
    }

    public function getEstablished() : ?int
    {
        return $this->get('established');
    }

    public function getDivisionOffline() : ?int
    {
        return $this->get('divisionOffline');
    }

    public function getDivisionOnline() : ?int
    {
        return $this->get('divisionOnline');
    }

    public function getPersonaName() : ?int
    {
        return $this->get('personaName');
    }

    public function getSquadList() : ?array
    {
        return $this->get('squadList');
    }

    public function getUnopenedPacks() : ?array
    {
        return $this->get('unopenedPacks');
    }

    public function getPurchased() : ?bool
    {
        return $this->get('purchased');
    }

    public function getReliability() : ?array
    {
        return $this->get('reliability');
    }

    public function getSeasonTicket() : ?bool
    {
        return $this->get('seasonTicket');
    }

    public function getAccountCreatedPlatformName() : ?string
    {
        return $this->get('accountCreatedPlatformName');
    }

    public function getFifaPointsFromLastYear() : ?int
    {
        return $this->get('fifaPointsFromLastYear');
    }

    public function getFifaPointsTransferredStatus() : ?int
    {
        return $this->get('fifaPointsTransferredStatus');
    }

    public function getUnassignedPileSize() : ?int
    {
        return $this->get('unassignedPileSize');
    }

    public function getFeature() : ?array
    {
        return $this->get('feature');
    }

    public function getSessionCoinsBankBalance() : ?int
    {
        return $this->get('sessionCoinsBankBalance');
    }
}
