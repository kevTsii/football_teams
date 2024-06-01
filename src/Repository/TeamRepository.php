<?php

namespace App\Repository;

use App\Data\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends AbstractCommonRepository
{
    public const ENTITY_CLASS = Team::class;
}
