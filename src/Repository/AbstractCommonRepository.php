<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class AbstractCommonRepository extends ServiceEntityRepository
{
    public const ENTITY_CLASS = null;

    public function __construct(ManagerRegistry $registry)
    {
        if(is_null(static::ENTITY_CLASS)){
            throw new \InvalidArgumentException('the ENTITY_CLASS constant must be defined.');
        }

        parent::__construct($registry, static::ENTITY_CLASS);
    }

    /**
     * Return only the query that is equivalent to findAll() default function.
     *
     * @param string $alias
     *
     * @return Query
     */
    public function getAllQuery(string $alias): Query
    {
        return $this->createQueryBuilder($alias)
            ->getQuery();
    }

    /**
     * Save entity's information.
     *
     * @param      $oEntity
     * @param bool $flush "True" if flushing wanted
     *
     * @return void
     */
    public function save($oEntity, bool $flush): void
    {
        $this->getEntityManager()->persist($oEntity);

        if($flush) $this->getEntityManager()->flush();
    }

    /**
     * Delete an existent entity.
     *
     * @param      $oEntity
     * @param bool $flush "True" if flushing wanted
     *
     * @return void
     */
    public function delete($oEntity, bool $flush): void
    {
        $this->getEntityManager()->remove($oEntity);

        if($flush) $this->getEntityManager()->flush();
    }
}