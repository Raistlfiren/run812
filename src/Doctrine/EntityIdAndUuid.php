<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

trait EntityIdAndUuid
{
    /**
     * The unique auto incremented primary key.
     *
     * @var int|null
     */
    #[Id]
    #[Column(type: 'integer', options: ['unsigned' => true])]
    #[GeneratedValue]
    protected $id;

    /**
     * The internal primary identity key.
     *
     * @var UuidInterface
     */
    #[Column(type: 'uuid', unique: true)]
    protected $uuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param UuidInterface $uuid
     *
     * @return self
     */
    public function setUuid(UuidInterface $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }
}