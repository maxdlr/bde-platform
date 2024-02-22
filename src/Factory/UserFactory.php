<?php

namespace App\Factory;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use DateTime;
use Faker\Factory;

class UserFactory
{
    static private User|array $user;

    static public function random(): User
    {
        $faker = Factory::create();
        $userRepository = new UserRepository();
        $users = $userRepository->findAll();
        return $faker->randomElement($users);

    }

    static public function generate(): User|array
    {
        return self::$user;
    }

    static public function make($number = 1): static
    {
        if ($number === 1) {
            self::$user = self::mountObjectBase();
        } else {

            $arrayOfUsers = [];
            for ($i = 0; $i < $number; $i++) {
                $arrayOfUsers[] = self::mountObjectBase();
            }
            self::$user = $arrayOfUsers;
        }

        return new static;
    }

    static public function withFirstname(string $firstname): static
    {
        self::distribute(
            fn(User $user) => $user->setFirstname($firstname)
        );
        return new static;
    }

    static public function withLastname(string $lastname): static
    {
        self::distribute(
            fn(User $user) => $user->setLastname($lastname)
        );
        return new static;
    }

    static public function withEmail(string $email): static
    {
        self::distribute(
            fn(User $user) => $user->setEmail($email)
        );
        return new static;
    }

    static public function withSignedUpOn(DateTime $signedUpOn): static
    {
        self::distribute(
            fn(User $user) => $user->setSignedUpOn($signedUpOn)
        );
        return new static;
    }

    static public function withPassword(string $password): static
    {
        self::distribute(
            fn(User $user) => $user->setPassword($password)
        );
        return new static;
    }

    static public function withIsVerified(bool $isVerified): static
    {
        self::distribute(
            fn(User $user) => $user->setIsVerified($isVerified)
        );
        return new static;
    }

    static public function withRole(RoleEnum $roleEnum): static
    {
        self::distribute(
            fn(User $user) => $user->setRoles($roleEnum->value)
        );
        return new static;
    }

    static private function mountObjectBase(): User
    {
        $faker = Factory::create();
        $user = new User();

        $user
            ->setEmail($faker->email())
            ->setRoles($faker->randomElement(RoleEnum::cases())->value)
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPassword('password')
            ->setIsVerified(1)
            ->setSignedUpOn($faker->dateTime());

        return $user;
    }


    static private function distribute(callable $expression): static
    {
        if (!self::isMany()) {
            $expression(self::$user);
        } else {
            foreach (self::$user as $user) {
                $expression($user);
            }
        }

        return new static;
    }

    private static function isMany(): bool
    {
        return !self::$user instanceof User;
    }
}