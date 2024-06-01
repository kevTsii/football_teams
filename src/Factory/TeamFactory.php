<?php

namespace App\Factory;

use App\Data\Constants\EntityProperty;
use App\Data\Entity\Team;
use App\Repository\CountryRepository;

class TeamFactory
{

    public function __construct(
        private readonly CountryRepository $countryRepository
    )
    {
    }

    /**
     * Create a new team and set all its properties.
     *
     * @param array $parameters
     *
     * @return Team
     */
    public function createTeam(array $parameters): Team
    {
        $team = new Team();

        $team->setName($parameters['name']);
        $team->setCountry($this->countryRepository->find((int)$parameters['country']));
        $team->setMoneyBalance((float)$parameters['moneyBalance']);

        return $team;
    }

    /**
     * Update an existing team.
     *
     * @param Team  $team
     * @param array $values As "property=>values" where property must be in camelCase.
     *
     * @return Team
     */
    public function updateTeam(Team $team, array $values): Team
    {
        foreach ($values as $property => $value){
            $function = 'set'.ucfirst($property);
            if(EntityProperty::TEAM['COUNTRY'] === $property) $value = $this->countryRepository->find((int)$value);
            $team->$function($value);
        }

        return $team;
    }
}