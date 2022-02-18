<?php

namespace App\Form;

use App\Data\ContactData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add("email", TextType::class, [
                "label" => "Adresse email"
            ])
            ->add("subject", TextType::class, [
                "label" => "Sujet du message"
            ])
            ->add("content", TextareaType::class, [
                "label" => "Message"
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => ContactData::class
        ]);
    }
}