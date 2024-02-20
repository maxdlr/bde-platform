<?php

namespace App\Entity;
class Event
{
    private int $id;
    private string $name;
    private string $description;
    private string $startDate;
    private string $endDate;
    private string $tag;
    private int $capacity;
    private int $ownerId;

    public function getId()
	{
		return $this->id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function setName(string $name): static
	{
		$this->name = $name;
        return $this;

	}
	public function getDescription()
	{
		return $this->description;
	}
	public function setDescription(string $description): static
	{
		$this->description = $description;
        return $this;

	}
	public function getStartDate()
	{
		return $this->startDate;
	}
	public function setStartDate(string $startDate): static
	{
		$this->startDate = $startDate;
        return $this;

	}
	public function getEndDate()
	{
		return $this->endDate;
	}
	public function setEndDate(string $endDate): static
	{
		$this->endDate = $endDate;
        return $this;

	}
	public function getTag()
	{
		return $this->tag;
	}
	public function setTag(string $tag): static
	{
		$this->tag = $tag;
        return $this;

	}
	public function getCapacity()
	{
		return $this->capacity;
	}
	public function setCapacity(int $capacity): static
	{
		$this->capacity = $capacity;
        return $this;
	}
	public function getOwnerId()
	{
		return $this->ownerId;
	}
	public function setOwnerId(int $ownerId): static
	{
		$this->ownerId = $ownerId;
        return $this;
	}

}