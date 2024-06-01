<?php

namespace App\Services\BusinessServices;

use App\Data\Entity\Player;
use App\Factory\PlayerFactory;
use App\Repository\PlayerRepository;
use Psr\Log\LoggerInterface;

class PlayerBS
{
    public function __construct(
        private readonly PlayerRepository $repository,
        private readonly PlayerFactory $playerFactory,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * Create a new player.
     *
     * @param array $parameters
     *
     * @return Player|null
     */
    public function createPlayer(array $parameters): ?Player
    {
        try{
            $player = $this->playerFactory->createPlayer($parameters);
            $this->repository->save($player, true);

            return $player;
        }catch (\Exception $e){
            $this->logger->error('Failed to create a new player. || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Update a player datas.
     *
     * @param Player $player
     * @param array  $parameters
     *
     * @return Player|null
     */
    public function updatePlayer(Player $player, array $parameters): ?Player
    {
        try{
            $this->playerFactory->updatePlayer($player, $parameters);
            $this->repository->save($player, true);

            return $player;
        }catch (\Exception $e){
            $this->logger->error('Failed to update Player with id : '.$player->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Delete an existent player.
     *
     * @param Player $player
     *
     * @return bool
     */
    public function deletePlayer(Player $player): bool
    {
        try{
            $this->repository->delete($player, true);
            return true;
        }catch (\Exception $e){
            $this->logger->error('Failed to delete Player with id : '.$player->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return false;
        }
    }
}