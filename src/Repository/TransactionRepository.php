<?php

namespace App\Repository;

use App\Data\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends AbstractCommonRepository
{
   public const ENTITY_CLASS = Transaction::class;
}
