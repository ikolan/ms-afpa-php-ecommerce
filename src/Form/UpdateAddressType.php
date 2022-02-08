<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                "required" => true,
                "label" => "Numéro"
            ])
            ->add('streetName', TextType::class, [
                "required" => true,
                "label" => "Nom de rue"
            ])
            ->add('addIn', TextType::class, [
                "required" => false,
                "label" => "Complément"
            ])
            ->add('postalCode', TextType::class, [
                "required" => true,
                "label" => "Code postal"
            ])
            ->add('city', TextType::class, [
                "required" => true,
                "label" => "Ville"
            ])
            ->add('country', CountryType::class, [
                "required" => true,
                "label" => "Pays"
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Modifier",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
