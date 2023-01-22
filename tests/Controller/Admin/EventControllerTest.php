<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\EventCrudController;
use App\Entity\Event;
use App\Tests\DatabaseTestCase;

class EventControllerTest extends DatabaseTestCase
{
    public function testEventCreate()
    {
        $eventCrudController = new EventCrudController();
        $object = $eventCrudController->createEntity(Event::class);
        $this->assertInstanceOf(Event::class, $object);
        $this->assertInstanceOf(\DateTime::class, $object->getDatetime());
    }
}