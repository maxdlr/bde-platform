<?php

use App\Entity\User;
use App\Mapping\User\UserDTO;
use App\Mapping\User\UserOTD;
use App\Repository\userRepository;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class UserDTOAndOTDTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanProcessUserObject()
    {
        $userRepository = new UserRepository();
        $faker = Factory::create();

        $firstname = $faker->firstName();
        $name = $faker->name();
        $email = $faker->email();
        $password = $faker->password();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = $faker->randomElement(["admin", "BDE Members", "students", "visitor"]);
        $isVerified = true;
        $signedUpDate = $faker->dateTime()->format('Y-m-d H:i:s');

        $userArray = [
            'firstname' => $firstname,
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
            'isVerified' => $isVerified,
            'signedUpDate' => $signedUpDate,
        ];

        $userRepository->insertOne($userArray);

        $userObject = $userRepository->findOneBy($userArray);

        self::assertInstanceOf(User::class, $userObject);

        self::assertSame($userObject->getFirstname(), $userArray['firstname']);
        self::assertSame($userObject->getName(), $userArray['name']);
        self::assertSame($userObject->getEmail(), $userArray['email']);
        self::assertSame($userObject->getPassword(), $userArray['password']);
        self::assertSame($userObject->getRole(), $userArray['role']);
        self::assertSame($userObject->getIsVerified(), $userArray['isVerified']);
        self::assertSame($userObject->getSignedUpDate(), $userArray['signedUpDate']);

        $lookHash = password_verify($password, $userArray['password']);
        self::assertSame(true, $lookHash);

        $userRepository->delete($userArray);
    }

    /**
     * @throws Exception
     */
    public function testCanProcessUserArray()
    {
        $user = new User();
        $faker = Factory::create();

        $firstname = $faker->firstName();
        $name = $faker->name();
        $email = $faker->email();
        $password = $faker->password();
        $role = $faker->randomElement(["admin", "BDE Members", "students", "visitor"]);
        $isVerified = 1;
        $signedUpDate = $faker->dateTime()->format('Y-m-d H:i:s');

        $user
            ->setFirstname($firstname)
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
            ->setRole($role)
            ->setIsVerified($isVerified)
            ->setSignedUpDate($signedUpDate);

        $userOTD = new UserOTD();

        $userArray = $userOTD->config($user)->process();

        self::assertIsArray($userArray);

        self::assertSame($user->getFirstname(), $userArray['firstname']);
        self::assertSame($user->getName(), $userArray['name']);
        self::assertSame($user->getEmail(), $userArray['email']);

        $lookHash = password_verify($user->getPassword(), $userArray['password']);
        self::assertSame(true, $lookHash);

        self::assertSame($user->getRole(), $userArray['role']);
        self::assertSame($user->getIsVerified(), $userArray['isVerified']);
        self::assertSame($user->getSignedUpDate(), $userArray['signedUpDate']);
    }
}