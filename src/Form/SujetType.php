<?php

namespace App\Form;

use App\Entity\Sujet;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SujetType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,$this->getConfiguration('','Titre de votre sujet'))
            ->add('image',FileType::class,$this->getConfiguration('','Image lié à votre sujet',['required'=>false]))
            ->add('description',TextareaType::class,$this->getConfiguration('','Une description pour votre sujet'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
