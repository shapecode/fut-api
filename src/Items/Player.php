<?php

namespace Shapecode\FUT\Client\Items;

/**
 * Class Player
 *
 * @package Shapecode\FUT\Client\Items
 * @author  Nikita Loges
 */
class Player extends Item
{

    public function getFormation()
    {
        return $this->get('formation');
    }

    public function getAssetId()
    {
        return $this->get('assetId');
    }

    public function getRating()
    {
        return $this->get('rating');
    }

    public function getMorale()
    {
        return $this->get('morale');
    }

    public function getFitness()
    {
        return $this->get('fitness');
    }

    public function getInjuryType()
    {
        return $this->get('injuryType');
    }

    public function getInjuryGames()
    {
        return $this->get('injuryGames');
    }

    public function getPreferredPosition()
    {
        return $this->get('preferredPosition');
    }

    public function getStatsList()
    {
        return $this->get('statsList');
    }

    public function getLifetimeStats()
    {
        return $this->get('lifetimeStats');
    }

    public function getTraining()
    {
        return $this->get('training');
    }

    public function getContract()
    {
        return $this->get('contract');
    }

    public function getSuspension()
    {
        return $this->get('suspension');
    }

    public function getAttributeList()
    {
        return $this->get('attributeList');
    }

    public function getTeamid()
    {
        return $this->get('teamid');
    }

    public function getRareflag()
    {
        return $this->get('rareflag');
    }

    public function getPlayStyle()
    {
        return $this->get('playStyle');
    }

    public function getLeagueId()
    {
        return $this->get('leagueId');
    }

    public function getAssists()
    {
        return $this->get('assists');
    }

    public function getLifetimeAssists()
    {
        return $this->get('lifetimeAssists');
    }

    public function getLoans()
    {
        return $this->get('loans');
    }

    public function getLoyaltyBonus()
    {
        return $this->get('loyaltyBonus');
    }

    public function getNation()
    {
        return $this->get('nation');
    }

    public function getSkillMoves()
    {
        return $this->get('skillmoves');
    }

    public function getWeakFootAbilityTypeCode()
    {
        return $this->get('weakfootabilitytypecode');
    }

    public function getAttackingWorkRate()
    {
        return $this->get('attackingworkrate');
    }

    public function getDefensiveWorkRate()
    {
        return $this->get('defensiveworkrate');
    }

    public function getPreferredFoot()
    {
        return $this->get('preferredfoot');
    }

    public function getItemType(): string
    {
        return 'player';
    }
}
