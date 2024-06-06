<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class AbstractCommonController extends AbstractController
{
    protected function renderFormView($oEntity, string $context, FormInterface $form, string $twigPath): Response
    {
        return $this->render($twigPath, [
            $context => $oEntity,
            'form' => $form
        ]);
    }
}