<?php

namespace App\Repository;

use App\Data\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends AbstractCommonRepository
{
    public const ENTITY_CLASS = Player::class;
}
