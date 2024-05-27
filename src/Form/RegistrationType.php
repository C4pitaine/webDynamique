<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,$this->getConfiguration('','Adresse E-mail'))
            ->add('username',TextType::class,$this->getConfiguration('','Nom / Prénom'))
            ->add('password',PasswordType::class,$this->getConfiguration('','Mot de passe',['toggle'=>true]))
            ->add('passwordConfirm',PasswordType::class,$this->getConfiguration('','Confirmer le mot de passe',['toggle'=>true]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
