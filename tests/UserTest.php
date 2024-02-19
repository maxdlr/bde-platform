<?php

use App\DB\DatabaseManager;
use App\DB\EntityManager;
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
        $john->setMailAdress("joe.cohen@gmail.com");

        $result = filter_var($john->getMailAdress(), FILTER_VALIDATE_EMAIL);

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

}