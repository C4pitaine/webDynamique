<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\ExosMusculation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ExosMusculationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,$this->getConfiguration('','Exercice muscu'))
            ->add('series',IntegerType::class,$this->getConfiguration('',"Nombre de séries",[
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 100
                ]
            ]))
            ->add('repetitions',IntegerType::class,$this->getConfiguration('',"Nombre de répétitions",[
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 100
                ]
            ]))
            ->add('poids',IntegerType::class,$this->getConfiguration('',"Poids",[
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 1000
                ]
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExosMusculation::class,
        ]);
    }
}
