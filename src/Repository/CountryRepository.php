<?php

namespace App\Repository;

use App\Data\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Country>
 */
class CountryRepository extends AbstractCommonRepository
{
    public const ENTITY_CLASS = Country::class;
}
