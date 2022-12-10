<?php

namespace App\Controller\Admin;

use App\Entity\RouteCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RouteCollectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RouteCollection::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('routes')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('multiple', true),
            BooleanField::new('saturdayRoute'),
        ];
    }

}
