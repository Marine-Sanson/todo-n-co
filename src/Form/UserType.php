<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{


    /**
     * Summary of buildForm
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array $options options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options=null): void
    {

        $builder
            ->add(
                'username', TextType::class, [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Nom'
                ]
            )
            ->add(
                'email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control mb-3',
                        'readonly' => true,
                    ],
                    'label' => 'Email'
                ]
            )
            ->add(
                'roles', ChoiceType::class, [
                    'attr' => [
                            'class' => 'form-control mb-3',
                        ],
                    'choices' => ['ADMIN' => 'ROLE_ADMIN', 'UTILISATEUR' => 'ROLE_USER'],
                    'mapped' => true,
                    'expanded' => true,
                    'multiple' => true,
                ]
            );

    }

    /**
     * Summary of configureOptions
     *
     * @param OptionsResolver $resolver resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );

    }


}
