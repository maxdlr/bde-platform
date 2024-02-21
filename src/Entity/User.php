<?php

namespace App\Entity;

use App\Attribute\AsEntity;
use App\Repository\UserRepository;
use App\Service\DB\Entity;
use DateTime;

#[AsEntity(repositoryClass: UserRepository::class)]
class User extends Entity
{
    private ?int $id = null;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $roles;
    private bool $isVerified;
    private DateTime $signedUpOn;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'firstname' => $this->getFirstName(),
            'lastname' => $this->getLastname(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            'isVerified' => $this->getIsVerified(),
            'signedUpOn' => $this->getSignedUpOn()->format('Y-m-d H:i:s')
        ];
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getSignedUpOn(): DateTime
    {
        return $this->signedUpOn;
    }

    public function setSignedUpOn(DateTime $signedUpOn): static
    {
        $this->signedUpOn = $signedUpOn;
        return $this;
    }
}