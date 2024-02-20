<?php

namespace App\Entity;

use App\Attribute\AsEntity;
use App\Repository\UserRepository;
use App\Service\DB\Entity;

#[AsEntity(repositoryClass: UserRepository::class)]
class User extends Entity
{
    private int $id;
    private string $firstname;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    private bool $isVerified;
    private string $signedUpDate;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hashPassword;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getSignedUpDate(): string
    {
        return $this->signedUpDate;
    }

    public function setSignedUpDate(string $signedUpDate): static
    {
        $this->signedUpDate = $signedUpDate;
        return $this;
    }
}