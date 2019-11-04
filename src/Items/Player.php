<?php

declare(strict_types=1);

namespace Shapecode\FUT\Client\Items;

class Player extends Item
{
    public function getFormation() : ?string
    {
        return $this->get('formation');
    }

    public function getAssetId() : ?int
    {
        return $this->get('assetId');
    }

    public function getRating() : ?int
    {
        return $this->get('rating');
    }

    public function getMorale() : ?int
    {
        return $this->get('morale');
    }

    public function getFitness() : ?int
    {
        return $this->get('fitness');
    }

    public function getInjuryType() : ?string
    {
        return $this->get('injuryType');
    }

    public function getInjuryGames() : ?int
    {
        return $this->get('injuryGames');
    }

    public function getPreferredPosition() : ?string
    {
        return $this->get('preferredPosition');
    }

    /**
     * @return mixed[]
     */
    public function getStatsList() : array
    {
        return $this->get('statsList') ?? [];
    }

    /**
     * @return mixed[]
     */
    public function getLifetimeStats() : array
    {
        return $this->get('lifetimeStats') ?? [];
    }

    public function getTraining() : ?int
    {
        return $this->get('training');
    }

    public function getContract() : ?int
    {
        return $this->get('contract');
    }

    public function getSuspension() : ?int
    {
        return $this->get('suspension');
    }

    /**
     * @return mixed[]
     */
    public function getAttributeList() : array
    {
        return $this->get('attributeList') ?? [];
    }

    public function getTeamid() : ?int
    {
        return $this->get('teamid');
    }

    public function getRareflag() : ?bool
    {
        return $this->get('rareflag');
    }

    public function getPlayStyle() : ?int
    {
        return $this->get('playStyle');
    }

    public function getLeagueId() : ?int
    {
        return $this->get('leagueId');
    }

    public function getAssists() : ?int
    {
        return $this->get('assists');
    }

    public function getLifetimeAssists() : ?int
    {
        return $this->get('lifetimeAssists');
    }

    public function getLoans() : ?int
    {
        return $this->get('loans');
    }

    public function getLoyaltyBonus() : ?bool
    {
        return (bool) $this->get('loyaltyBonus');
    }

    public function getNation() : ?int
    {
        return $this->get('nation');
    }

    public function getSkillMoves() : ?int
    {
        return $this->get('skillmoves');
    }

    public function getWeakFootAbilityTypeCode() : ?int
    {
        return $this->get('weakfootabilitytypecode');
    }

    public function getAttackingWorkRate() : ?int
    {
        return $this->get('attackingworkrate');
    }

    public function getDefensiveWorkRate() : ?int
    {
        return $this->get('defensiveworkrate');
    }

    public function getPreferredFoot() : ?int
    {
        return $this->get('preferredfoot');
    }

    public function getItemType() : string
    {
        return 'player';
    }
}
