<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\LocationCrudController;
use App\Tests\DatabaseTestCase;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocationControllerTest extends DatabaseTestCase
{
    public function testConfigureFields()
    {
        $locationController = new LocationCrudController();
        $fields = $locationController->configureFields('test');
        $this->assertCount(2, $fields);
        $this->assertInstanceOf(TextField::class, $fields[0]);
        $this->assertInstanceOf(AssociationField::class, $fields[1]);
    }
}