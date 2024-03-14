<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
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
            ->add('title')
            ->add('content')
            ->add('isDone');

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
                'data_class' => Task::class,
            ]
        );

    }


}
