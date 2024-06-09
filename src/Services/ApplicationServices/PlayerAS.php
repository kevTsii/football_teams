<?php

namespace App\Services\ApplicationServices;

use App\Data\DataTransferObject\PlayerDTO;
use App\Repository\PlayerRepository;
use App\Services\BusinessServices\PlayerBS;

class PlayerAS
{
    public function __construct(
        private readonly PlayerBS $playerBS,
        private readonly PlayerRepository $playerRepository
    )
    {
    }

    /**
     * Return the paginated datas with Jquery DataTable pagination parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    public function getDataTableResponse(array $parameters): array
    {
        $players = iterator_to_array(
            $this->playerBS->getAllPlayersPaginate(
                (int)$parameters['offset'] + 1,
                (int)$parameters['limit'],
                (int)$parameters['team']
            )->getCurrentPageResults()
        );

        $toReturn['datas'] = array_map(function ($player){
            return new PlayerDTO($player);
        }, $players);

        $totalRows = $this->playerRepository->countAll('p');
        $toReturn['recordsTotal'] = $totalRows;
        $toReturn['recordsFiltered'] = $totalRows;

        return $toReturn;
    }

}