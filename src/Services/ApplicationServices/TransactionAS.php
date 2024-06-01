<?php

namespace App\Services\ApplicationServices;

use App\Factory\TransactionFactory;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Repository\TransactionRepository;
use App\Services\BusinessServices\TransactionBS;

class TransactionAS
{
    public function __construct(
        private readonly TransactionFactory $transactionFactory,
        private readonly TransactionRepository $transactionRepository,
        private readonly TransactionBS $transactionBS,
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    )
    {
    }

    /**
     * The transaction processus.
     *
     * @param array $parameters
     *
     * @return bool
     */
    public function doTransaction(array $parameters): bool
    {
        $player = $this->playerRepository->find((int)$parameters['player']);
        $buyer = $this->teamRepository->find((int)$parameters['buyer']);
        $seller = $this->teamRepository->find((int)$parameters['seller']);
        $amount = (float)$parameters['amount'];

        if(!$this->transactionBS->canBuy($buyer, $amount)) return false;

        //the transaction
        $this->transactionBS->decreaseBuyerBalance($buyer, $amount);
        $this->transactionBS->increaseSellerBalance($seller, $amount);
        $player->setTeam($buyer);

        //Save the transaction in the DataBase
        $transaction = $this->transactionFactory->createTransaction($parameters);

        //Save all those modifications
        $this->teamRepository->save($buyer);
        $this->teamRepository->save($seller);
        $this->playerRepository->save($player);
        $this->transactionRepository->save($transaction, true);

        return true;
    }
}