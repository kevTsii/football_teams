<?php

namespace App\Factory;


use App\Data\Constants\EntityProperty;
use App\Data\Entity\Transfer;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;

class TransferFactory
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    )
    {
    }

    /**
     * Create a new transfer and set all its properties.
     *
     * @param array $informations
     *
     * @return Transfer
     */
    public function createTransaction(array $informations): Transfer
    {
        $transfer = new Transfer();

        $transfer->setPlayer($this->playerRepository->find((int)$informations['player']));
        $transfer->setSeller($this->teamRepository->find((int)$informations['seller']));
        $transfer->setBuyer($this->teamRepository->find((int)$informations['buyer']));
        $transfer->setAmount((float)$informations['amount']);

        return $transfer;
    }

    /**
     * Update an existing Transfer.
     *
     * @param Transfer $transfer
     * @param array    $values As "property=>values" where property must be in camelCase.
     *
     * @return Transfer
     */
    public function updateTransaction(Transfer $transfer, array $values): Transfer
    {
        foreach ($values as $property => $value){
            $function = 'set'.ucfirst($property);
            if(EntityProperty::TRANSACTION['PLAYER'] === $property) {
                $value = $this->playerRepository->find((int)$value);
            } elseif (in_array($property, [EntityProperty::TRANSACTION['BUYER'], EntityProperty::TRANSACTION['SELLER']], true)){
                $value = $this->teamRepository->find((int)$value);
            }
            $transfer->$function($value);
        }

        return $transfer;
    }
}