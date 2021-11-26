<?php

namespace App\Form;

use App\Entity\ExerciseReference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseReferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('name')
            ->add('description')
            ->add('material')
            ->add('strainessFactor', null, ['empty_data' => 0.4])
            ->add('isBodyweightExercise')
            ->add('isOnlyIsometric')
            ->add('isValidated')
            ->add('author');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'data_class' => ExerciseReference::class,
        ]);
    }
}
