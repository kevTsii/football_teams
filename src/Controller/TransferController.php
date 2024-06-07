<?php

namespace App\Controller;

use App\Data\Constants\Context;
use App\Data\Entity\Transfer;
use App\Exception\BalanceNotEnoughException;
use App\Form\TransferType;
use App\Services\ApplicationServices\TransferAS;
use App\Services\BusinessServices\TransferBS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transfer')]
class TransferController extends AbstractCommonController
{
    public function __construct(
        private readonly TransferAS $transferAS,
        private readonly TransferBS $transferBS
    )
    {
    }

    #[Route('/create', name: 'app_transfer_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $transfer = new Transfer();
        $form = $this->createForm(TransferType::class, $transfer);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{
                $this->transferAS->doTransaction($request->request->all()['transfer']);
                return $this->redirectToRoute('app_transfer_list');
            }catch(BalanceNotEnoughException $balanceException){
                $this->addFlash('error', $balanceException->getMessage());
            }catch(\Exception $e){
                $this->addFlash('error', 'Unhandled error occurred.');
            }
        }

        return $this->renderFormView($transfer, Context::TRANSFER_CONTEXT, $form, 'transfers/create.html.twig');
    }

    #[Route('/list', name: 'app_transfer_list', methods: ['GET'])]
    public function showList(Request $request): Response
    {

    }

    #[Route('/update/{transfer}', name: 'app_transfer_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Transfer $transfer): Response
    {
        //EMPTY : we suppose that a transfer is not editable
        //Function created for evolution purpose
    }
}