<?php

namespace App\Services\BusinessServices;

use App\Data\Entity\Country;
use App\Repository\CountryRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;

class CountryBS
{
    public function __construct(
        private readonly CountryRepository $repository,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * Create a new country.
     *
     * @param string $name
     *
     * @return Country|null
     */
    public function createCountry(string $name): ?Country
    {
        try{
            $country = new Country();
            $country->setName($name);
            $this->repository->save($country, true);

            return $country;
        }catch (\Exception $e){
            $this->logger->error('Failed to create a new country. || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Get the all countries (paginate).
     *
     * @param int $page
     * @param int $limit
     *
     * @return iterable
     */
    public function getAllCountriesPaginate(int $page, int $limit): iterable
    {
        return (new Pagerfanta(new QueryAdapter($this->repository->getAllQuery('c'))))
            ->setMaxPerPage($limit)
            ->setCurrentPage($page);
    }

    /**
     * Update a country datas.
     *
     * @param Country $country
     * @param string  $name
     *
     * @return Country|null
     */
    public function updateCountry(Country $country, string $name): ?Country
    {
        try{
            $country->setName($name);
            $this->repository->save($country, true);

            return $country;
        }catch (\Exception $e){
            $this->logger->error('Failed to update Country with id : '.$country->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Delete an existent country.
     *
     * @param Country $country
     *
     * @return bool
     */
    public function deleteCountry(Country $country): bool
    {
        try{
            $this->repository->delete($country, true);
            return true;
        }catch (\Exception $e){
            $this->logger->error('Failed to delete Country with id : '.$country->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return false;
        }
    }
}