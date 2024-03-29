<?php

namespace App\Entity;

use App\Attribute\AsEntity;
use App\Repository\EventRepository;
use App\Service\DB\Entity;
use DateTime;

#[AsEntity(repositoryClass: EventRepository::class)]
class Event extends Entity
{
    private ?int $id = null;
    private string $name;
    private string $description;
    private DateTime $startDate;
    private DateTime $endDate;
    private string $tag;
    private int $capacity;
    private int $ownerId;

    private string $fileName;
    private float $fileSize;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;

    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;

    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;

    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): static
    {
        $this->endDate = $endDate;
        return $this;

    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;
        return $this;

    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): static
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): Event
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getFileSize(): float
    {
        return $this->fileSize;
    }

    public function setFileSize(float $fileSize): Event
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'startDate' => $this->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => $this->getEndDate()->format('Y-m-d H:i:s'),
            'tag' => $this->getTag(),
            'capacity' => $this->getCapacity(),
            'owner_id' => $this->getOwnerId(),
            'fileName' => $this->getFileName(),
            'fileSize' => $this->getFileSize()
        ];
    }
}
