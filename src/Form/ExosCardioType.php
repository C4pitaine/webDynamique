<?php

namespace App\Form;

use App\Entity\ExosCardio;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ExosCardioType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,$this->getConfiguration('','Exercice cardio'))
            ->add('time',IntegerType::class,$this->getConfiguration('',"Temps d'exercice",[
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 1440
                ]
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExosCardio::class,
        ]);
    }
}
