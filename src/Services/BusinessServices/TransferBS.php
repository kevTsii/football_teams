<?php

namespace App\Services\BusinessServices;

use App\Data\Entity\Team;
use App\Data\Entity\Transfer;
use App\Factory\TransferFactory;
use App\Repository\TransferRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;

class TransferBS
{
    public function __construct(
        private readonly TransferRepository $repository,
        private readonly TransferFactory $transferFactory,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * Create a new transfer.
     *
     * @param array $parameters
     *
     * @return Transfer|null
     */
    public function createTransaction(array $parameters): ?Transfer
    {
        try{
            $transfer = $this->transferFactory->createTransaction($parameters);
            $this->repository->save($transfer, true);

            return $transfer;
        }catch (\Exception $e){
            $this->logger->error('Failed to create a new transfer. || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Get the all transfers (paginate).
     *
     * @param int $page
     * @param int $limit
     *
     * @return iterable
     */
    public function getAllTransactionsPaginate(int $page, int $limit): iterable
    {
        return (new Pagerfanta(new QueryAdapter($this->repository->getAllQuery('tr'))))
            ->setMaxPerPage($limit)
            ->setCurrentPage($page);
    }

    /**
     * Update a transfer datas.
     *
     * @param Transfer $transfer
     * @param array    $parameters
     *
     * @return Transfer|null
     */
    public function updateTransaction(Transfer $transfer, array $parameters): ?Transfer
    {
        try{
            $this->transferFactory->updateTransaction($transfer, $parameters);
            $this->repository->save($transfer, true);

            return $transfer;
        }catch (\Exception $e){
            $this->logger->error('Failed to update Transfer with id : '.$transfer->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return null;
        }
    }

    /**
     * Delete an existent transfer.
     *
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function deleteTransaction(Transfer $transfer): bool
    {
        try{
            $this->repository->delete($transfer, true);
            return true;
        }catch (\Exception $e){
            $this->logger->error('Failed to delete Transfer with id : '.$transfer->getId().' || Fatal Error : '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
            return false;
        }
    }

    /**
     * Check if the buyer can buy the player.
     *
     * @param Team  $buyer
     * @param float $transferAmount
     *
     * @return bool
     */
    public function canBuy(Team $buyer, float $transferAmount): bool
    {
        return $buyer->getMoneyBalance() > $transferAmount;
    }

    /**
     * Add the transfer amount in the seller money balance.
     *
     * @param Team  $seller
     * @param float $transferAmount
     *
     * @return void
     */
    public function increaseSellerBalance(Team $seller, float $transferAmount): void
    {
        $seller->setMoneyBalance($seller->getMoneyBalance() - $transferAmount);
    }

    /**
     * Remove the transfer amount from the buyer money balance.
     *
     * @param Team  $buyer
     * @param float $transferAmount
     *
     * @return void
     */
    public function decreaseBuyerBalance(Team $buyer, float $transferAmount): void
    {
        $buyer->setMoneyBalance($buyer->getMoneyBalance() + $transferAmount);
    }
}