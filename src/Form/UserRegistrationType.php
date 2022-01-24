<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
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
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "La confirmation du mot de passe est incorrect.",
                "required" => true,
                "first_options" => [
                    "label" => "Mot de passe",
                    "attr" => [
                        "onChange" => "validate();",
                    ]
                ],
                "second_options" => [
                    "label" => "Confirmation du mot de passe",
                    "attr" => [
                        "onChange" => "validate();",
                    ]
                ]
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
