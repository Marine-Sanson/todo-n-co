<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{

    /**
     * Summary of buildForm
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array $options options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                    ],
                    'label' => 'Email'
                ]
            )
            ->add(
                'password', PasswordType::class, [
                                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control mb-3',
                    ],
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Entrez votre mot de passe',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'La longueur de votre mot de passe doit être d\'au moins {{ limit }} caractères',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'roles', ChoiceType::class, [
                    'attr' => [
                        'class' => 'form-control mb-3',
                    ],
                    'choices' => ['ADMIN' => 'ROLE_ADMIN', 'UTILISATEUR' => 'ROLE_USER'],
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
