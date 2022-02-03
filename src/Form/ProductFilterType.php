<?php

namespace App\Form;

use App\Data\ProductFilterData;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('search', TextType::class, [
                "required" => false,
                "label" => "Recherche"
            ])
            ->add('categories', EntityType::class, [
                "class" => Category::class,
                "multiple" => true,
                "expanded" => true,
                "label" => "Categories"
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Filtrer"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => ProductFilterData::class,
            "method" => "GET",
            "csrf_protection" => false
        ]);
    }
}
