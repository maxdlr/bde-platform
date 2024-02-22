<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Twig\Environment;
use DateTime;

class UserController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/user/new', name: 'app_user_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['new-user-submit']) && $_POST['new-user-submit'] == 'new-user') {
            $user = new User();
            $userRepository = new UserRepository();

            $stringCurrentDate = date('Y-m-d H:i:s');
            $dateCurrentDate = DateTime::createFromFormat('Y-m-d H:i:s', $stringCurrentDate);

            $user
                ->setFirstname($_POST['firstname'])
                ->setLastname($_POST['lastname'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password'])
                ->setRoles("student")
                ->setIsVerified(1)
                ->setSignedUpOn($dateCurrentDate);

            if ($userRepository->insertOne($user)) {
                $this->redirect('/admin/user/index');
            }
        }

        $roles = $this->roleRepository->findAll();
        return $this->twig->render('user/user-new.html.twig', [
            'tags' => $roles,
        ]);
    }
}