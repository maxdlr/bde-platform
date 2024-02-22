<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\User;
use App\Enum\TagEnum;
use App\Factory\Data\EventData;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use DateTime;
use Faker\Factory;

class EventFactory
{
    static private Event|array $event;

    static public function random(): Event
    {
        $faker = Factory::create();
        $eventRepository = new EventRepository();
        $events = $eventRepository->findAll();
        return $faker->randomElement($events);

    }

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

    static public function withFileName(string $fileName): static
    {
        self::distribute(
            fn(Event $event) => $event->setFileName($fileName)
        );
        return new static;
    }

    static public function withFileSize(string $fileSize): static
    {
        self::distribute(
            fn(Event $event) => $event->setFileName($fileSize)
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
        $eventData = new EventData();
        $event = new Event();
        $faker = Factory::create();

        $users = $userRepository->findAll();
        $user = $faker->randomElement($users);

        $event
            ->setName($eventData->buildTitle())
            ->setDescription($eventData->buildDescription())
            ->setStartDate($faker->dateTime())
            ->setEndDate($faker->dateTime())
            ->setTag($faker->randomElement(TagEnum::cases())->value)
            ->setCapacity($faker->randomNumber(2))
            ->setOwnerId($user->getId())
            ->setFileName($faker->imageUrl())
            ->setFileSize($faker->randomFloat(2));

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