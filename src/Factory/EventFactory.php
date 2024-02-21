<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\User;
use App\Enum\TagEnum;
use App\Repository\UserRepository;
use DateTime;
use Faker\Factory;

class EventFactory
{
    static private Event|array $event;

    static public function generate(): Event|array
    {
        return self::$event;
    }

    static public function make($number = 1): static
    {
        if ($number === 1) {
            self::$event = self::mountObjectBase();
        } else {

            $arrayOfEvents = [];
            for ($i = 0; $i < $number; $i++) {
                $arrayOfEvents[] = self::mountObjectBase();
            }
            self::$event = $arrayOfEvents;
        }

        return new static;
    }

    static public function withName(string $name): static
    {
        self::distribute(
            fn(Event $event) => $event->setName($name)
        );
        return new static;
    }

    static public function withDescription(string $description): static
    {
        self::distribute(
            fn(Event $event) => $event->setDescription($description)
        );
        return new static;
    }

    static public function withStartDate(DateTime $startDate): static
    {
        self::distribute(
            fn(Event $event) => $event->setStartDate($startDate)
        );
        return new static;
    }

    static public function withEndDate(DateTime $endDate): static
    {
        self::distribute(
            fn(Event $event) => $event->setEndDate($endDate)
        );
        return new static;
    }

    static public function withTag(string $tag): static
    {
        $validTag = TagEnum::tryFrom($tag);

        self::distribute(
            fn(Event $event) => $event->setTag($validTag->value)
        );
        return new static;
    }

    static public function withCapacity(int $number): static
    {
        self::distribute(
            fn(Event $event) => $event->setCapacity($number)
        );
        return new static;
    }

    static public function withOwner(User $owner): static
    {
        self::distribute(
            fn(Event $event) => $event->setOwnerId($owner->getId())
        );
        return new static;
    }

    static private function mountObjectBase(): Event
    {
        $userRepository = new UserRepository();

        $faker = Factory::create();
        $event = new Event();
        $username = $faker->userName();


        $userRepository->insertOne(
            UserFactory::make()->withFirstname($username)->generate()
        );
        
        $user = $userRepository->findOneBy(['firstname' => $username]);

        $event
            ->setName($faker->domainWord())
            ->setDescription($faker->paragraph())
            ->setStartDate($faker->dateTime())
            ->setEndDate($faker->dateTime())
            ->setTag($faker->word())
            ->setCapacity($faker->randomNumber(2))
            ->setOwnerId($user->getId());

        return $event;
    }

    static private function distribute(callable $expression): static
    {
        if (!self::isMany()) {
            $expression(self::$event);
        } else {
            foreach (self::$event as $event) {
                $expression($event);
            }
        }

        return new static;
    }

    private static function isMany(): bool
    {
        return !self::$event instanceof Event;
    }
}