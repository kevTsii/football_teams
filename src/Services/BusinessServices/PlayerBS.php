<?php

namespace App\Services\BusinessServices;

use App\Data\Constants\Translation;
use App\Data\Entity\Player;
use App\Data\Entity\Team;
use App\Exception\HasTransferException;
use App\Factory\PlayerFactory;
use App\Factory\TranslatorTrait;
use App\Repository\PlayerRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;

class PlayerBS
{
    use TranslatorTrait;

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
     * Get the all players (paginate).
     *
     * @param int $page
     * @param int $limit
     * @param int $teamId
     *
     * @return Pagerfanta
     */
    public function getAllPlayersPaginate(int $page, int $limit, int $teamId): Pagerfanta
    {
        return (new Pagerfanta(new QueryAdapter($this->repository->getAllQuery('p', ['team' => $teamId]))))
            ->setCurrentPage($page)
            ->setMaxPerPage($limit);
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
     * @return void
     * @throws HasTransferException
     */
    public function deletePlayer(Player $player): void
    {
        if(count($player->getTransfers()) > 0){
            throw new HasTransferException($this->translate(
                'exception.has_transfer',
                ['%name%' => $player->getName().' '.$player->getSurname()],
                Translation::PLAYER_DOMAIN
            )
            );
        }

        $this->repository->delete($player, true);
    }


    /**
     * @param Team $team
     *
     * @return array
     */
    public function getByTeam(Team $team): array
    {
        return $this->repository->getAllQuery('pl', ['team' => $team])->getResult();
    }
}