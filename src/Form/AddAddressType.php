<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                "required" => true,
                "label" => "Numero"
            ])
            ->add('streetName', TextType::class, [
                "required" => true,
                "label" => "Nom de la rue"
            ])
            ->add('addIn', TextType::class, [
                "required" => false,
                "label" => "Complement d'adresse"
            ])
            ->add('postalCode', TextType::class, [
                "required" => true,
                "label" => "Code Postal"
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
                "label" => "Ajouter"
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
