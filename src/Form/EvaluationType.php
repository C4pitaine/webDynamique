<?php

namespace App\Form;

use App\Entity\Evaluation;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvaluationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note',IntegerType::class,$this->getConfiguration('','Note de 1 à 5',[
                'attr' => [
                    'step' => 1,
                    'min' => 1,
                    'max' => 5
                ]
            ]))
            ->add('avis',TextareaType::class,$this->getConfiguration('','Avis'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
