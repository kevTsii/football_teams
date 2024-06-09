<?php

namespace App\Form;

use App\Data\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{

    public function __construct(
        private readonly RequestStack $requestStack
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isEdit = 'app_countries_show' === $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $isEdit
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'row_attr' => [
                    'class' => 'form-group mb-3 user-select-none'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Country::class
        ]);
    }
}