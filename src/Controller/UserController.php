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

    #[Route('/admin/users', name: 'app_users', httpMethod: ['GET'])]
    public function index()
    {
        $users = $this->userRepository->findAll();

        return $this->twig->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}