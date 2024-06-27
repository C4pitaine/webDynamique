<?php

namespace App\Form;

use App\Entity\Seance;
use App\Form\ExosCardioType;
use App\Form\ApplicationType;
use App\Form\ExosMusculationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SeanceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,$this->getConfiguration('','Nom de votre séance'))
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Date de votre séance',
                ],
            ])
            ->add('exosMusculations', CollectionType::class, [
                'entry_type' => ExosMusculationType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('exosCardios', CollectionType::class, [
                'entry_type' => ExosCardioType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
