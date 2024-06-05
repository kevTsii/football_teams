<?php

namespace App\Services\ApplicationServices;

use App\Repository\PlayerRepository;
use App\Services\BusinessServices\PlayerBS;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerAS
{
    public function __construct(
        private readonly PlayerBS $playerBS,
        private readonly SerializerInterface $serializer,
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
        $toReturn['datas'] = $this->serializer->serialize(
            $this->playerBS->getAllPlayersPaginate(
                (int)$parameters['offset'] + 1,
                (int)$parameters['limit']
            )->getCurrentPageResults(),
            'json',
            ['groups' => ['no-team-serialized']]);

        $totalRows = $this->playerRepository->countAll('p');
        $toReturn['recordsTotal'] = $totalRows;
        $toReturn['recordsFiltered'] = $totalRows;

        return $toReturn;
    }

}