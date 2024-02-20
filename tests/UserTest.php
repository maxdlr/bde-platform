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
        $john->setEmail("joe.cohen@gmail.com");

        $result = filter_var($john->getEmail(), FILTER_VALIDATE_EMAIL);

        self::assertNotFalse($result, "Email invalid !");
    }

}