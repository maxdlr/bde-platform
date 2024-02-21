<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\UserRepository;
use Twig\Environment;

class UserController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct($twig);
    }
}