<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ArticleModifyType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class,$this->getConfiguration('Image','Image de l\'article',['required'=>false]))
            ->add('title',TextType::class,$this->getConfiguration('Titre','Titre de l\'article'))
            ->add('link',UrlType::class,$this->getConfiguration('Lien','Url de l\'article'))
            ->add('description',TextareaType::class,$this->getConfiguration('Description','Description de l\'article',['required'=>false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
