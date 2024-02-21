<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\UserRepository;
use Twig\Environment;

class UserController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/user/dashboard', name: 'app_user_dashboard', httpMethod: ['GET'])]
    public function dahsboard(): string
    {


        return $this->twig->render('user/index.html.twig', [
        ]);
    }
}