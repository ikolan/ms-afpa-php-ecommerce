<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                "label" => "Prénom",
            ])
            ->add('lastName', TextType::class, [
                "label" => "Nom",
            ])
            ->add('email', EmailType::class, [
                "label" => "Adresse Email",
            ])
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe",
            ])
            ->add("passwordConfirm", PasswordType::class, [
                "label" => "Confirmation du mot de passe",
                "mapped" => false,
            ])
            ->add("submit", SubmitType::class, [
                "label" => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
