<?php

namespace App\Controller\Admin;

use App\Entity\RunningGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RunningGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RunningGroup::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            AssociationField::new('routes')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('multiple', true)
        ];
    }

}
