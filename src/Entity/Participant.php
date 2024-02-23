<?php

namespace App\Entity;

use App\Attribute\AsEntity;
use App\Repository\ParticipantRepository;
use App\Service\DB\Entity;

#[AsEntity(repositoryClass: ParticipantRepository::class)]
class Participant extends Entity
{
    private int $id;
    private int $eventId;
    private int $userId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): static
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }
}