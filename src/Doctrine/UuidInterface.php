<?php

namespace App\Doctrine;

use Ramsey\Uuid\UuidInterface as BaseUuidInterface;

interface UuidInterface {
    public function getUuid(): BaseUuidInterface;

    public function setUuid(BaseUuidInterface $uuid);
}