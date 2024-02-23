<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Twig\Environment;
use DateTime;

class AdminUserController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly UserRepository  $userRepository,
        private readonly EventRepository $eventRepository,
        private readonly RoleRepository  $roleRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/user/index', name: 'app_admin_user_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $userObjects = $this->userRepository->findAll();
        $users = array_map(fn(User $user): array => $user->toArray(), $userObjects);
        $events = $this->eventRepository->findAll();


        return $this->twig->render('admin/index.html.twig', [
            'items' => $users,
            'users' => $users,
            'entityName' => 'user',
            'events' => $events,
        ]);
    }

    #[Route('/admin/user/new', name: 'app_admin_user_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        $roles = $this->roleRepository->findAll();

        if (isset($_POST['admin-new-user-submit']) && $_POST['admin-new-user-submit'] == 'admin-new-user') {

            $userRepository = new UserRepository();
            if($userRepository->findOneBy(['email' => $_POST['email']])){
                $this->addFlash("danger", "L'adresse mail saisie est déja occupée par l'un des utilisateurs !");
                return $this->twig->render('user/user-new.html.twig', [
                    'tags' => $roles,
                    'admin' => true,
                    'flashbag' => $_SESSION["flashbag"]
                ]);
            } else {

                $user = new User();
                $stringCurrentDate = date('Y-m-d H:i:s');
                $dateCurrentDate = DateTime::createFromFormat('Y-m-d H:i:s', $stringCurrentDate);

                $user
                    ->setFirstname($_POST['firstname'])
                    ->setLastname($_POST['lastname'])
                    ->setEmail($_POST['email'])
                    ->setPassword($_POST['password'])
                    ->setRoles($_POST['role'])
                    ->setIsVerified(true)
                    ->setSignedUpOn($dateCurrentDate);

                if ($userRepository->insertOne($user)) {
                    $this->redirect('/admin/user/index');
                }
            }
        }

        return $this->twig->render('user/user-new.html.twig', [
            'tags' => $roles,
            'admin' => true,
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit', httpMethod: ['GET', 'POST'])]
    public function edit(int $idUser): string
    {
        $userRepository = new UserRepository();
        $user = $userRepository->findOneBy(['id' => $idUser]);

        $roles = $this->roleRepository->findAll();

        if (isset($_POST['edit-user-submit']) && $_POST['edit-user-submit'] == 'edit-user') {

            $userRepository = new UserRepository();
            $mailOriginUser = $user->getEmail();
            if($_POST['email'] != $mailOriginUser && $userRepository->findOneBy(['email' => $_POST['email']])){
                $this->addFlash("danger", "L'adresse mail saisie est déja occupée par l'un des utilisateurs !");
                return $this->twig->render('admin/edit/user-edit.html.twig', [
                    'tags' => $roles,
                    'flashbag' => $_SESSION["flashbag"]
                ]);
            } else {
                $updatedUserArray = [
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'roles' => $_POST['role']
                ];

                if ($_POST['isVerified'] === "on") {
                    $updatedUserArray['isVerified'] = 1;
                } else {
                    $updatedUserArray['isVerified'] = 0;
                }

                if ($userRepository->update($updatedUserArray, $user)) {
                    $this->redirect('/admin/user/index');
                }
            }
        } elseif (!is_null($user)) {
            return $this->twig->render('admin/edit/user-edit.html.twig', [
                'item' => $user,
                'roles' => $roles
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete', httpMethod: ['POST'])]
    public function delete(int $idUser): void
    {
        $userRepository = new userRepository();
        $userToDelete = $userRepository->findOneBy(['id' => $idUser]);

        if ($userRepository->delete($userToDelete)) {
            $this->redirect('/admin/user/index');
        }
    }

}