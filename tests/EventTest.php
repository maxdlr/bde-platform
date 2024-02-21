<?php

use App\Entity\Event;
use App\Enum\RoleEnum;
use App\Factory\EventFactory;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\DB\Entity;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

class EventTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanFindAllEvent()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $name = $faker->word();

        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event
                ->setName($name)
                ->setDescription($faker->paragraph())
                ->setStartDate($faker->dateTime())
                ->setEndDate($faker->dateTime())
                ->setTag($faker->word())
                ->setCapacity($faker->randomNumber(2))
                ->setOwnerId($faker->randomElement(
                    $userRepository->findBy(
                        ['roles' => RoleEnum::ROLE_MANAGER->value]
                    ))->getId());

            $eventRepository->insertOne($event);
        }

        $eventObjects = $eventRepository->findAll();

        self::assertNotNull($eventObjects);
        self::assertIsArray($eventObjects);
        self::assertInstanceOf(Entity::class, $eventObjects[rand(0, count($eventObjects) - 1)]);

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateEventAndFindOneBy()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = $faker->randomElement($userRepository->findBy(['roles' => RoleEnum::ROLE_MANAGER->value]))->getId();

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);
        $eventRepository->insertOne($event);

        $read = $eventRepository->findOneBy(['name' => $name]);

        self::assertSame($name, $read->getName());
        self::assertSame($description, $read->getDescription());
        self::assertSame($startDate->format('Y-m-d H:i:s'), $read->getStartDate()->format('Y-m-d H:i:s'));
        self::assertSame($endDate->format('Y-m-d H:i:s'), $read->getEndDate()->format('Y-m-d H:i:s'));
        self::assertSame($tag, $read->getTag());
        self::assertSame($capacity, (int)$read->getCapacity());
        self::assertSame($owner_id, (int)$read->getOwnerId());

        $eventRepository->delete($event);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateEventAndFindBy()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);

        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event
                ->setId($i + 999)
                ->setName($name)
                ->setDescription($description)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setTag($tag)
                ->setCapacity($capacity)
                ->setOwnerId($faker->randomElement($userRepository->findBy(['roles' => RoleEnum::ROLE_MANAGER->value]))->getId());
            $eventRepository->insertOne($event);
        }

        $events = $eventRepository->findBy(['name' => $name]);

        foreach ($events as $eventItem) {
            assertSame($event->getName(), $eventItem->getName());
            assertSame($event->getDescription(), $eventItem->getDescription());
            assertSame($event->getStartDate()->format('Y-m-d H:i:s'), $eventItem->getStartDate()->format('Y-m-d H:i:s'));
            assertSame($event->getEndDate()->format('Y-m-d H:i:s'), $eventItem->getEndDate()->format('Y-m-d H:i:s'));
            assertSame($event->getTag(), $eventItem->getTag());
            assertSame($event->getCapacity(), $eventItem->getCapacity());
        }

        self::assertCount(10, $events);

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanUpdateEvent()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = $faker->randomElement($userRepository->findBy(['roles' => RoleEnum::ROLE_MANAGER->value]))->getId();

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventRepository->insertOne($event);

        $eventRepository->update(['description' => 'new description'], $event);

        $read = $eventRepository->findOneBy(['name' => $name]);

        assertSame($name, $read->getName());
        assertSame('new description', $read->getDescription());

        $eventRepository->delete(['name' => $name]);
    }

    /**
     * @throws Exception
     */
    public function testCanDeleteEvent()
    {
        $eventRepository = new EventRepository();
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = $faker->randomElement($userRepository->findBy(['roles' => RoleEnum::ROLE_MANAGER->value]))->getId();

        $event = new Event();
        $event
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $eventRepository->insertOne($event);

        $eventRepository->delete($event);

        self::assertNull($eventRepository->findOneBy(['name' => $name]));
    }

    public function testCanEventToArray()
    {
        $faker = Factory::create();
        $userRepository = new UserRepository();

        $name = $faker->word();
        $description = $faker->paragraph();
        $startDate = $faker->dateTime();
        $endDate = $faker->dateTime();
        $tag = $faker->word();
        $capacity = $faker->randomNumber(2);
        $owner_id = $faker->randomElement($userRepository->findBy(['roles' => RoleEnum::ROLE_MANAGER->value]))->getId();

        $event = new Event();
        $event
            ->setId($faker->randomNumber(1) + 999)
            ->setName($name)
            ->setDescription($description)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setTag($tag)
            ->setCapacity($capacity)
            ->setOwnerId($owner_id);

        $event = $event->toArray();

        self::assertIsArray($event);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateOneEventWithFactory()
    {
        $event = EventFactory::make()->withName('Maxime')->generate();

        $eventRepository = new EventRepository();
        $eventRepository->insertOne($event);

        $result = $eventRepository->findOneBy(['name' => $event->getName()]);

        assertSame('Maxime', $result->getName());

        $eventRepository->delete(['name' => $event->getName()]);
    }

    public function testCanCreateManyWithFactory()
    {
        $events = EventFactory::make(30)->withName('Maxime')->generate();
        $eventRepository = new EventRepository();

        foreach ($events as $event) {
            $eventRepository->insertOne($event);
        }

        $result = $eventRepository->findBy(['name' => $event->getName()]);

        self::assertCount(30, $result);

        $eventRepository->delete(['name' => $event->getName()]);
    }

    public function testCanCreateWithDescription()
    {
        $eventRepository = new EventRepository();
        $event = EventFactory::make()->withDescription(
            'oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo ')->generate();
        $eventRepository->insertOne($event);

        $result = $eventRepository->findOneBy(['description' => $event->getDescription()]);

        assertSame('oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo oooooooooooooooooooooooooooo ', $result->getDescription());

        $eventRepository->delete(['description' => $event->getDescription()]);
    }

    public function testCanCreateWithTag()
    {
        $event = EventFactory::make()->withTag('soirée')->generate();

        $eventRepository = new EventRepository();
        $eventRepository->insertOne($event);

        $result = $eventRepository->findOneBy(['tag' => $event->getTag()]);

        assertSame('soirée', $result->getTag());

        $eventRepository->delete(['tag' => $event->getTag()]);
    }

    public function testCanCreateWithDates()
    {
        $event = EventFactory::make()->withStartDate(new DateTime('now'))->generate();

        $eventRepository = new EventRepository();
        $eventRepository->insertOne($event);

        $result = $eventRepository->findOneBy(['startDate' => $event->getStartDate()->format('Y-m-d H:i:s')]);

        assertSame($event->getName(), $result->getName());

        $eventRepository->delete(['name' => $event->getName()]);
    }
}