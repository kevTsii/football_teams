<?php

namespace App\Factory;


use App\Data\Constants\EntityProperty;
use App\Data\Entity\Transaction;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;

class TransactionFactory
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    )
    {
    }

    /**
     * Create a new transaction and set all its properties.
     *
     * @param array $informations
     *
     * @return Transaction
     */
    public function createTransaction(array $informations): Transaction
    {
        $transaction = new Transaction();

        $transaction->setPlayer($this->playerRepository->find((int)$informations['player']));
        $transaction->setSeller($this->teamRepository->find((int)$informations['seller']));
        $transaction->setBuyer($this->teamRepository->find((int)$informations['buyer']));
        $transaction->setPrice((float)$informations['price']);

        return $transaction;
    }

    /**
     * Update an existing Transaction.
     *
     * @param Transaction $transaction
     * @param array       $values As "property=>values" where property must be in camelCase.
     *
     * @return Transaction
     */
    public function updateTransaction(Transaction $transaction, array $values): Transaction
    {
        foreach ($values as $property => $value){
            $function = 'set'.ucfirst($property);
            if(EntityProperty::TRANSACTION['PLAYER'] === $property) {
                $value = $this->playerRepository->find((int)$value);
            } elseif (in_array($property, [EntityProperty::TRANSACTION['BUYER'], EntityProperty::TRANSACTION['SELLER']], true)){
                $value = $this->teamRepository->find((int)$value);
            }
            $transaction->$function($value);
        }

        return $transaction;
    }
}