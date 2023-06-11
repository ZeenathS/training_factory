<?php

namespace App\Form;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('duration')
            ->add('extra_costs')
            ->add('title')
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->add('saveAndExit', SubmitType::class, ['label' => 'Save & Exit'])
//            ->add('cancel', SubmitType::class, ['label' => 'Cancel', 'attr' => ['class' => 'btn-danger']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
