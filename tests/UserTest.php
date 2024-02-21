<?php

use App\Entity\User;
use App\Mapping\User\UserOTD;
use App\Repository\userRepository;
use Faker\Factory;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * Allows to check the validity of user's email
     * @throws Exception
     */
    public function testEmailFormat()
    {
        $john = new User();
        $john->setEmail("joe.cohen@gmail.com");
        $resultJohn = filter_var($john->getEmail(), FILTER_VALIDATE_EMAIL);

        self::assertNotFalse($resultJohn, "Email invalid !");


        $tom = new User();
        $tom->setEmail("@tombruton.com");
        $resultTom = filter_var($tom->getEmail(), FILTER_VALIDATE_EMAIL);

        self::assertFalse($resultTom, "Email invalid !");
    }

    /**
     * @throws Exception
     */
    public function testCanProcessUserObject()
    {
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $firstname = $faker->firstName();
        $password = $faker->password();

        $user = UserFactory::make()->withFirstname($firstname)->withPassword($password)->generate();
        $userRepository->insertOne($user);

        $userFromDb = $userRepository->findOneBy(['firstname' => $firstname]);

        $lookHash = password_verify($password, $userFromDb->getPassword());
        self::assertInstanceOf(User::class, $userFromDb);

        self::assertSame($userFromDb->getFirstname(), $user->getFirstname());
        self::assertSame($userFromDb->getLastname(), $user->getLastname());
        self::assertSame($userFromDb->getEmail(), $user->getEmail());
        self::assertSame(true, $lookHash);
        self::assertSame($userFromDb->getRoles(), $user->getRoles());
        self::assertSame($userFromDb->getIsVerified(), $user->getIsVerified());
        self::assertSame(
            $userFromDb->getSignedUpOn()->format('Y-m-d H:i:s'),
            $user->getSignedUpOn()->format('Y-m-d H:i:s')
        );

        $userRepository->delete($user);
    }
}