<?php

namespace App\Factory;

use App\Data\Constants\EntityProperty;
use App\Data\Entity\Player;
use App\Repository\TeamRepository;

class PlayerFactory
{
    public function __construct(
        private readonly TeamRepository $teamRepository
    )
    {
    }

    /**
     * Create a new player and set all its properties
     * @param array $propertiesValue
     *
     * @return Player
     */
    public function createPlayer(array $propertiesValue): Player
    {
        $player = new Player();

        $player->setName($propertiesValue['name']);
        $player->setSurname($propertiesValue['surname']);
        $player->setTeam($this->teamRepository->find((int)$propertiesValue['team']));

        return $player;
    }

    /**
     * Update an existing player.
     * @param Player $player
     * @param array $values As "property=>values" where property must be in camelCase.
     *
     * @return Player
     */
    public function updateTeam(Player $player, array $values): Player
    {
        foreach ($values as $property => $value){
            $function = 'set'.ucfirst($property);
            if(EntityProperty::PLAYER['TEAM'] === $property) $value = $this->teamRepository->find((int)$value);
            $player->$function($value);
        }

        return $player;
    }
}