<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\Interested;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Faker\Factory;

class InterestedFactory
{

    static private Interested|array $interested;

    static public function generate(): Interested|array
    {
        return self::$interested;
    }

    static public function make($number = 1): static
    {
        if ($number === 1) {
            self::$interested = self::mountObjectBase();
        } else {

            $arrayOfInteresteds = [];
            for ($i = 0; $i < $number; $i++) {
                $arrayOfInteresteds[] = self::mountObjectBase();
            }
            self::$interested = $arrayOfInteresteds;
        }

        return new static;
    }

    static public function withUser(User $user): static
    {
        self::distribute(
            fn(Interested $interested) => $interested->setUserId($user->getId())
        );
        return new static;
    }

    static public function withEvent(Event $event): static
    {
        self::distribute(
            fn(Interested $interested) => $interested->setEventId($event->getId())
        );
        return new static;
    }

    static private function mountObjectBase(): Interested
    {
        $faker = Factory::create();

        $username = $faker->userName();
        $userRepository = new UserRepository();

        foreach (UserFactory::make(10)->withFirstname($username)->generate() as $user) {
            $userRepository->insertOne($user);
        }

        $users = $userRepository->findBy(['firstname' => $username]);

        $name = $faker->word();
        $eventRepository = new EventRepository();

        foreach (EventFactory::make(10)->withName($name)->generate() as $event) {
            $eventRepository->insertOne($event);
        }

        $events = $eventRepository->findBy(['name' => $name]);

        $interested = new Interested();
        $interested
            ->setUserId($faker->randomElement($users)->getId())
            ->setEventId($faker->randomElement($events)->getId());

        return $interested;
    }

    static private function distribute(callable $expression): static
    {
        if (!self::isMany()) {
            $expression(self::$interested);
        } else {
            foreach (self::$interested as $interested) {
                $expression($interested);
            }
        }

        return new static;
    }

    private static function isMany(): bool
    {
        return !self::$interested instanceof Interested;
    }
}