<?php

namespace App\Controller\Admin;

use App\Entity\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RouteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Route::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT);
    }
}
