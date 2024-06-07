<?php

namespace App\Services\ApplicationServices;

use App\Factory\TransactionFactory;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Repository\TransferRepository;
use App\Services\BusinessServices\TransferBS;

class TransferAS
{
    public function __construct(
        private readonly TransferFactory $transferFactory,
        private readonly TransferRepository $transferRepository,
        private readonly TransferBS $transferBS,
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    )
    {
    }

    /**
     * The transfer processus.
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

        //the transfer
        $this->transferBS->decreaseBuyerBalance($buyer, $amount);
        $this->transferBS->increaseSellerBalance($seller, $amount);
        $player->setTeam($buyer);

        //Save the transfer in the DataBase
        $transfer = $this->transferFactory->createTransaction($parameters);

        //Save all those modifications
        $this->teamRepository->save($buyer);
        $this->teamRepository->save($seller);
        $this->playerRepository->save($player);
        $this->transferRepository->save($transfer, true);

        return true;
    }
}