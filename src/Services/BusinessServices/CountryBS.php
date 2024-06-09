<?php

namespace App\Services\BusinessServices;

use App\Data\Constants\Translation;
use App\Data\Entity\Country;
use App\Exception\NotEmptyException;
use App\Factory\TranslatorTrait;
use App\Repository\CountryRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CountryBS
{
    use TranslatorTrait;

    public function __construct(
        private readonly CountryRepository $repository,
        private readonly LoggerInterface $logger,
        private readonly ParameterBagInterface $parameterBag
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
     * @return void
     * @throws NotEmptyException
     */
    public function deleteCountry(Country $country): void
    {
        if(count($country->getTeams()) > 0) {
            throw new NotEmptyException(
                $this->translate(
                    'exception.not_empty',
                    ['%name%' => $country->getName()],
                    Translation::COUNTRY_DOMAIN
                )
            );
        }

        $this->repository->delete($country, true);
    }

    /**
     * Read the json file countries.json that will be imported inside DataBase
     *
     * @return array
     * @throws \Exception
     */
    public function readJsonFile(): array
    {
        $sFile = $this->parameterBag->get('countries_json');

        if(!file_exists($sFile)){
            throw new \Exception('The file '.$sFile.' does not exist.');
        }

        $sContent = file_get_contents($sFile);
        if($sContent){
            $aContent = json_decode($sContent, true);
        }else{
            $aContent = [];
        }

        return $aContent;
    }
}