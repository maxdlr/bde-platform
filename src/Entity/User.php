<?php

namespace App\Entity;

class User
{
    private int $id;
    private string $firstname;
    private string $name;
    private string $mailAdress;
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

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMailAdress(): string
    {
        return $this->mailAdress;
    }

    public function setMailAdress(string $mailAdress): void
    {
        $this->mailAdress = $mailAdress;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hashPassword;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function getSignedUpDate(): string
    {
        return $this->signedUpDate;
    }

    public function setSignedUpDate(string $signedUpDate): void
    {
        $this->signedUpDate = $signedUpDate;
    }
}