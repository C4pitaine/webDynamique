<?php

namespace App\Form;

use App\Entity\Entrainement;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EntrainementModifyType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,$this->getConfiguration('Titre',"Titre de l'entrainement"))
            ->add('image',FileType::class,$this->getConfiguration("Image","Image de l'entrainement",['required' => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entrainement::class,
        ]);
    }
}
