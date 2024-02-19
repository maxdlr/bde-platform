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
	private function setId(int $id)
	{
		$this->id = $id;
	}
	public function getName()
	{
		return $this->name;
	}
	public function setName(string $name)
	{
		$this->name = $name;
	}
	public function getDescription()
	{
		return $this->description;
	}
	public function setDescription(string $description)
	{
		$this->description = $description;
	}
	public function getStartDate()
	{
		return $this->startDate;
	}
	public function setStartDate(string $startDate)
	{
		$this->startDate = $startDate;
	}
	public function getEndDate()
	{
		return $this->endDate;
	}
	public function setEndDate(string $endDate)
	{
		$this->endDate = $endDate;
	}
	public function getTag()
	{
		return $this->tag;
	}
	public function setTag(string $tag)
	{
		$this->tag = $tag;
	}
	public function getCapacity()
	{
		return $this->capacity;
	}
	public function setCapacity(int $capacity)
	{
		$this->capacity = $capacity;
	}
	public function getOwnerId()
	{
		return $this->ownerId;
	}
	public function setOwnerId(int $ownerId)
	{
		$this->ownerId = $ownerId;
	}
}
