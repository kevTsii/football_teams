<?php

namespace App\Services\BusinessServices;

use App\Data\Entity\Transaction;
use App\Factory\TransactionFactory;
use App\Repository\TransactionRepository;
use Psr\Log\LoggerInterface;

class TransactionBS
{
    public function __construct(
        private readonly TransactionRepository $repository,
        private readonly TransactionFactory $transactionFactory,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * Create a new transaction.
     *
     * @param array $parameters
     *
     * @return Transaction|null
     */
    public function createTransaction(array $parameters): ?Transaction
    {
        try{
            $transaction = $this->transactionFactory->createTransaction($parameters);
            $this->repository->save($transaction, true);

            return $transaction;
        }catch (\Exception $e){
            $this->logger->error('Failed to create a new transaction. || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Update a transaction datas.
     *
     * @param Transaction $transaction
     * @param array       $parameters
     *
     * @return Transaction|null
     */
    public function updateTransaction(Transaction $transaction, array $parameters): ?Transaction
    {
        try{
            $this->transactionFactory->updateTransaction($transaction, $parameters);
            $this->repository->save($transaction, true);

            return $transaction;
        }catch (\Exception $e){
            $this->logger->error('Failed to update Transaction with id : '.$transaction->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Delete an existent transaction.
     *
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function deleteTransaction(Transaction $transaction): bool
    {
        try{
            $this->repository->delete($transaction, true);
            return true;
        }catch (\Exception $e){
            $this->logger->error('Failed to delete Transaction with id : '.$transaction->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return false;
        }
    }
}