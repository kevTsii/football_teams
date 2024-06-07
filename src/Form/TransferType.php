<?php

namespace App\Form;

use App\Data\Entity\Player;
use App\Data\Entity\Team;
use App\Data\Entity\Transfer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', NumberType::class, [
                'row_attr' => [
                    'class' => "form-group mb-3",
                ],
                'label_attr' => [
                    'class' => "form-label",
                ],
                'attr' => [
                    'class' => "form-control",
                ]
            ])
            ->add('seller', EntityType::class, [
                'class' => Team::class,
                'placeholder' => "",
                'row_attr' => [
                    'class' => "form-group mb-3"
                ],
                'label_attr' => [
                    'class' => "form-label"
                ],
                'attr' => [
                    'class' => "form-select",
                    'id' => 'seller-select'
                ]
            ])
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'placeholder' => "",
                'row_attr' => [
                    'class' => "form-group mb-3"
                ],
                'label_attr' => [
                    'class' => "form-label"
                ],
                'attr' => [
                    'class' => "form-select",
                    'id' => "player-select"
                ]
            ])
            ->add('buyer', EntityType::class, [
                'class' => Team::class,
                'placeholder' => "",
                'row_attr' => [
                    'class' => "form-group mb-3"
                ],
                'label_attr' => [
                    'class' => "form-label"
                ],
                'attr' => [
                    'class' => "form-select"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfer::class,
        ]);
    }
}