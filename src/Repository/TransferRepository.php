<?php

namespace App\Repository;

use App\Data\Entity\Transfer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Transfer>
 */
class TransferRepository extends AbstractCommonRepository
{
   public const ENTITY_CLASS = Transfer::class;
}
