<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Faker\Factory;

class ParticipantFactory
{
    static public function random(): Participant
    {
        $faker = Factory::create();
        $participantRepository = new ParticipantRepository();
        $participants = $participantRepository->findAll();
        return $faker->randomElement($participants);

    }

    static private Participant|array $participant;

    static public function generate(): Participant|array
    {
        return self::$participant;
    }

    static public function make($number = 1): static
    {
        if ($number === 1) {
            self::$participant = self::mountObjectBase();
        } else {

            $arrayOfParticipants = [];
            for ($i = 0; $i < $number; $i++) {
                $arrayOfParticipants[] = self::mountObjectBase();
            }
            self::$participant = $arrayOfParticipants;
        }

        return new static;
    }

    static public function withUser(User $user): static
    {
        self::distribute(
            fn(Participant $participant) => $participant->setUserId($user->getId())
        );
        return new static;
    }

    static public function withEvent(Event $event): static
    {
        self::distribute(
            fn(Participant $participant) => $participant->setEventId($event->getId())
        );
        return new static;
    }

    static private function mountObjectBase(): Participant
    {
        $faker = Factory::create();
        $user = UserFactory::random();
        $event = EventFactory::random();

        $participant = new Participant();
        $participant
            ->setUserId($user->getId())
            ->setEventId($event->getId());

        return $participant;
    }

    static private function distribute(callable $expression): static
    {
        if (!self::isMany()) {
            $expression(self::$participant);
        } else {
            foreach (self::$participant as $participant) {
                $expression($participant);
            }
        }

        return new static;
    }

    private static function isMany(): bool
    {
        return !self::$participant instanceof Participant;
    }
}