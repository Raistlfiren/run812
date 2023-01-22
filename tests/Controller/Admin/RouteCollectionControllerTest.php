<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\RouteCollectionCrudController;
use App\Tests\DatabaseTestCase;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RouteCollectionControllerTest extends DatabaseTestCase
{
    public function testConfigureFields()
    {
        $routeController = new RouteCollectionCrudController();
        $fields = $routeController->configureFields('test');
        $this->assertCount(2, $fields);
        $this->assertInstanceOf(TextField::class, $fields[0]);
        $this->assertInstanceOf(AssociationField::class, $fields[1]);
    }
}