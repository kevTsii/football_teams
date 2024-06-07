<?php

namespace App\Form;

use App\Data\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{

    public function __construct(
        private readonly RequestStack $requestStack
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = 'app_players_show' === $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $builder
            ->add('name', TextType::class, [
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
            ])
            ->add('surname', TextType::class, [
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}