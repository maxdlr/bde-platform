<?php

namespace App\Entity;

class Participant
{
    private int $id;
    private int $eventId;
    private int $userId;

    public function getId(): int
    {
        return $this->id;
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