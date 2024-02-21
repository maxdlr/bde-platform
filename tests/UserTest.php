<?php

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * Allows to check the validity of user's email
     * @throws Exception
     */
    public function testEmailFormat()
    {
        $john = new \App\Entity\User();
        $john->setEmail("joe.cohen@gmail.com");

        $result = filter_var($john->getEmail(), FILTER_VALIDATE_EMAIL);

        self::assertNotFalse($result, "Email invalid !");
    }

    /**
     * Allows to check if the password entered by the user is equal to one hashed in db
     * @throws Exception
     */
    public function testCanCheckPasswordHash()
    {
        $joe = new \App\Entity\User();
        $joe->setPassword("p05vb8*>");

        $result = password_verify("p05vb8*>", $joe->getPassword());

        self::assertSame(true, $result);
    }

    /**
     * @throws Exception
     */
    public function testCanCreateOneWithFactory()
    {
        $user = UserFactory::make()->withFirstname('Maxime')->generate();

        $userRepository = new UserRepository();
        $userRepository->insertOne($user);

        $result = $userRepository->findOneBy(['firstname' => $user->getFirstname()]);

        self::assertSame('Maxime', $result->getFirstname());

        $userRepository->delete(['firstname' => $user->getFirstname()]);
    }

    public function testCanCreateManyWithFactory()
    {
        $users = UserFactory::make(30)->withFirstname('Maxime')->generate();
        $userRepository = new UserRepository();

        foreach ($users as $user) {
            $userRepository->insertOne($user);
        }

        $result = $userRepository->findBy(['firstname' => $user->getFirstname()]);

        self::assertCount(30, $result);

        $userRepository->delete(['firstname' => $user->getFirstname()]);
    }
}