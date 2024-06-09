<?php

namespace App\Controller;

use App\Data\Constants\Context;
use App\Data\Constants\Translation;
use App\Data\Entity\Country;
use App\Factory\TranslatorTrait;
use App\Form\CountryType;
use App\Services\BusinessServices\CountryBS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/countries')]
class CountryController extends AbstractCommonController
{
    use TranslatorTrait;

    public function __construct(
        private readonly CountryBS $countryBS,
    )
    {
    }

    #[Route('/list', name: 'app_countries_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;
        $limit = $request->query->get('limit') ?? 10;

        return $this->render('countries/index.html.twig', [
            'countries' => $this->countryBS->getAllCountriesPaginate($page, $limit)
        ]);
    }

    #[Route('/create', name: 'app_countries_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{
                $this->countryBS->createCountry($country->getName());

                $this->addFlash('success', $this->translate(
                    'success.create',
                    ['%context%' => 'country'],
                    Translation::FLASH_MESSAGES_DOMAIN
                ));

                return $this->redirectToRoute('app_countries_index');
            }catch(\Exception $e){
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderFormView(
            $country,
            Context::COUNTRY_CONTEXT,
            $form,
            'countries/form.html.twig'
        );
    }

    #[Route('/show/{country}', name: 'app_countries_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Country $country): Response
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->countryBS->updateCountry($country, $request->request->all()['country']['name']);

            $this->addFlash('success', $this->translate(
                'success.edit',
                ['%name%' => $country->getName()],
                Translation::FLASH_MESSAGES_DOMAIN
            ));

            return $this->redirectToRoute('app_countries_index');
        }

        return $this->renderFormView(
            $country,
            Context::COUNTRY_CONTEXT,
            $form,
            'countries/form.html.twig'
        );
    }

    #[Route('/delete/{country}', name: 'app_countries_delete', methods: ['GET', 'DELETE'])]
    public function delete(Country $country): Response
    {
        try{
            $this->countryBS->deleteCountry($country);

            $this->addFlash('success', $this->translate(
                'success.delete',
                ['%context%' => 'country'],
                Translation::FLASH_MESSAGES_DOMAIN
            ));

            return $this->redirectToRoute('app_countries_index');
        }catch (\Exception $e){
            $this->addFlash('error', $e->getMessage());

            return  $this->redirectToRoute('app_countries_show', ['country' => $country->getId()]);
        }
    }
}