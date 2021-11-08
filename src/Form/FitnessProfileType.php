<?php

namespace App\Form;

use App\Entity\FitnessProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FitnessProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('experience')
            ->add('weight')
            ->add('age')
            ->add('goals')
            ->add('hamstringExperience')
            ->add('quadricepsExperience')
            ->add('calfExperience')
            ->add('absExperience')
            ->add('forearmExperience')
            ->add('bicepsExperience')
            ->add('tricepsExperience')
            ->add('shoulderExperience')
            ->add('chestExperience')
            ->add('backExperience')
            ->add('user');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FitnessProfile::class,
            'csrf_protection' => false

        ]);
    }
}
