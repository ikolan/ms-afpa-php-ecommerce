<?php

namespace App\Controller\Admin;

use App\Entity\Shipper;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShipperCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shipper::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('name'),
            MoneyField::new('price')->setCurrency("EUR"),
            TextareaField::new('description')
        ];
    }
}
