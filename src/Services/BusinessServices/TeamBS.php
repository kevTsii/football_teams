<?php

namespace App\Services\BusinessServices;

use App\Data\Entity\Team;
use App\Factory\TeamFactory;
use App\Repository\TeamRepository;
use Psr\Log\LoggerInterface;

class TeamBS
{
    public function __construct(
        private readonly TeamRepository $repository,
        private readonly TeamFactory $teamFactory,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * Create a new team.
     *
     * @param array $parameters
     *
     * @return Team|null
     */
    public function createTeam(array $parameters): ?Team
    {
        try{
            $team = $this->teamFactory->createTeam($parameters);
            $this->repository->save($team, true);

            return $team;
        }catch (\Exception $e){
            $this->logger->error('Failed to create a new team. || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Update a team datas.
     *
     * @param Team  $team
     * @param array $parameters
     *
     * @return Team|null
     */
    public function updateTeam(Team $team, array $parameters): ?Team
    {
        try{
            $this->teamFactory->updateTeam($team, $parameters);
            $this->repository->save($team, true);

            return $team;
        }catch (\Exception $e){
            $this->logger->error('Failed to update Team with id : '.$team->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Delete an existent team.
     *
     * @param Team $team
     *
     * @return bool
     */
    public function deleteTeam(Team $team): bool
    {
        try{
            $this->repository->delete($team, true);
            return true;
        }catch (\Exception $e){
            $this->logger->error('Failed to delete Team with id : '.$team->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return false;
        }
    }
}