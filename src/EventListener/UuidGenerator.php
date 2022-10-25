<?php

namespace App\EventListener;

use App\Doctrine\UuidInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Ramsey\Uuid\Uuid;

class UuidGenerator implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $lifecycleEventArgs)
    {
        $entity = $lifecycleEventArgs->getObject();

        if (! $entity instanceof UuidInterface) {
            return;
        }

        $uuid = Uuid::uuid4();
        $entity->setUuid($uuid);
    }
}