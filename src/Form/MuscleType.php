<?php

namespace App\Form;

use App\Entity\Muscle;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MuscleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,$this->getConfiguration('Nom','Nom du muscle'))
            ->add('image',FileType::class,$this->getConfiguration('Image',"L'image du muscle"))
            ->add('description',TextareaType::class,$this->getConfiguration('Description',"La description du muscle"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Muscle::class,
        ]);
    }
}
