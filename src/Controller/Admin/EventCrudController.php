<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $upcomingSaturday = (new \DateTime('now'))
            ->modify('next Saturday')->setTime(7, 0);
        $entity = parent::createEntity($entityFqcn);
        $entity->setDateTime($upcomingSaturday);

        return $entity;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('datetime'),
            AssociationField::new('routeCollection')
                ->setFormTypeOption('choice_label', 'name')
                ->formatValue(function($value, $entity) {
                    return $entity->getRouteCollection()->getName();
                }),
        ];
    }

}
