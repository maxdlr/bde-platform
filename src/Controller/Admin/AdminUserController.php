<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\User;
use App\Mapping\User\UserOTD;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Twig\Environment;

class AdminUserController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/user/index', name: 'app_admin_user_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $userObjects = $this->userRepository->findAll();
        $users = array_map(fn(User $user): array => $user->toArray(), $userObjects);

        return $this->twig->render('admin/index.html.twig', [
            'items' => $users,
            'entityName' => 'user'
        ]);
    }

    #[Route('/admin/user/edit', name: 'app_admin_user_edit', httpMethod: ['GET', 'POST'])]
    public function edit(): string
    {
        if (isset($_POST['edit-user-submit']) && $_POST['edit-user-submit'] == 'edit-user') {
            $userRepository = new UserRepository();
            $user = $userRepository->findOneBy(['id' => $_POST['edit-user-input']]);
        }

        $roles = $this->roleRepository->findAll();

        return $this->twig->render('admin/edit/user-edit.html.twig', [
            'item' => $user,
            'roles' => $roles
        ]);
    }
}