<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("currentPassword", PasswordType::class, [
                "label" => "Mot de passe actuelle",
                "mapped" => false
            ])
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "required" => true,
                "first_options" => [
                    "label" => "Nouveau mot de passe",
                    "attr" => [
                        "onChange" => "validate();"
                    ]
                ],
                "second_options" => [
                    "label" => "Confirmation du mot de passe",
                    "attr" => [
                        "onChange" => "validate();"
                    ]
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Modifier"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
