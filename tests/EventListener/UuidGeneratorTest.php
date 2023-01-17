<?php

namespace App\Tests\EventListener;

use App\Entity\Route;
use App\Entity\User;
use App\EventListener\UuidGenerator;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class UuidGeneratorTest extends TestCase
{
    public function testSubscribedEvents()
    {
        $uuidGenerator = new UuidGenerator();

        $this->assertContains(Events::prePersist, $uuidGenerator->getSubscribedEvents());
    }

    public function testPrePersist()
    {
        $route = new Route();
        $lifeCycle = $this->getMockBuilder(LifecycleEventArgs::class)
            ->onlyMethods(['getObject'])->disableOriginalConstructor()
            ->getMock();
        $lifeCycle->expects($this->once())->method('getObject')->willReturn($route);
        $uuidGenerator = new UuidGenerator();

        $uuidGenerator->prePersist($lifeCycle);

        $this->assertInstanceOf(UuidInterface::class, $route->getUuid());
    }

    public function testPrePersistNoUuid()
    {
        $user = new User();
        $user2 = clone $user;
        $lifeCycle = $this->getMockBuilder(LifecycleEventArgs::class)
            ->onlyMethods(['getObject'])->disableOriginalConstructor()
            ->getMock();
        $lifeCycle->expects($this->once())->method('getObject')->willReturn($user);
        $uuidGenerator = new UuidGenerator();

        $uuidGenerator->prePersist($lifeCycle);

        $this->assertEquals($user2, $user);
    }
}