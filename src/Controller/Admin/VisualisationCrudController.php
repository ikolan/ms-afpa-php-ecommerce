<?php

namespace App\Controller\Admin;

use App\Entity\Visualisation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VisualisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Visualisation::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),
            AssociationField::new('product'),
            TextField::new('legend'),
            ImageField::new('path', "Image")
                ->setBasePath("visualisations/")
                ->setUploadDir("public/visualisations/")
                ->setUploadedFileNamePattern("[randomhash]-[slug].[extension]")
                ->hideWhenUpdating()
        ];
    }
}
