<?php

namespace App\Form;

use App\Data\ResetPasswordData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add("email", EmailType::class, [
                "label" => "Votre adresse email"
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer un lien de restauration"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => ResetPasswordData::class
        ]);
    }
}