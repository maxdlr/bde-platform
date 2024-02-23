<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Service\Mail\MailManager;
use Twig\Environment;
use DateTime;

class UserController extends AbstractController
{
    private User|null|bool $currentUser;

    public function __construct(
        Environment                            $twig,
        private readonly RoleRepository        $roleRepository,
        private readonly EventRepository       $eventRepository,
        private readonly ParticipantRepository $participantRepository,
        private readonly InterestedRepository  $interestedRepository,
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();
    }


    #[Route('/user/new', name: 'app_user_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['new-user-submit']) && $_POST['new-user-submit'] == 'new-user') {
            $user = new User();
            $userRepository = new UserRepository();
            $mailManager = new MailManager;

            $stringCurrentDate = date('d-m-Y H:i:s');
            $dateCurrentDate = DateTime::createFromFormat('d-m-Y H:i:s', $stringCurrentDate);

            $user
                ->setFirstname($_POST['firstname'])
                ->setLastname($_POST['lastname'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password'])
                ->setRoles("student")
                ->setIsVerified(false)
                ->setSignedUpOn($dateCurrentDate);

            if ($userRepository->insertOne($user)) {
                $token = md5(uniqid(rand(), true));

                $mailManager->sendValidateMail($_POST['email'], $token);

                $this->redirect('/admin/user/index');
            }
        }

        $roles = $this->roleRepository->findAll();
        return $this->twig->render('user/user-new.html.twig', [
            'tags' => $roles,
        ]);
    }


    #[Route('/user/login', name: 'app_user_login', httpMethod: ['GET', 'POST'])]
    public function connection(): string
    {
        $this->clearFlashs();

        if (isset($_POST['connect-user-submit']) && $_POST['connect-user-submit'] == 'connect-user') {

            $userRepository = new UserRepository();

            $user = $userRepository->findOneBy(['email' => $_POST['email']]);
            if (!is_null($user)) {
                $verifyHashPassword = password_verify($_POST['password'], $user->getPassword());
                if ($verifyHashPassword === true) {
                    $_SESSION["user_connected"] = $user->getEmail();

                    $this->redirect('/');
                } else {
                    $this->addFlash("danger", "Le mot de passe saisi ne correspond pas à l'adresse mail");
                }
            } else {
                $this->addFlash("danger", "L'adresse mail saisie ne correspond à aucun compte");
            }

            return $this->twig->render('user/login.html.twig', [
                'flashbag' => $_SESSION["flashbag"]
            ]);
        }

        return $this->twig->render('user/login.html.twig', []);
    }

    #[Route('/user/logout', name: 'app_user_logout', httpMethod: ['GET'])]
    public function disconnection(): string
    {
        session_destroy();

        header("Location: /");
        exit();
    }


    #[Route('/user/dashboard', name: 'app_user_dashboard', httpMethod: ['GET'])]
    public function dahsboard(): string
    {
        $this->redirectIfForbidden();

        $participants = $this->participantRepository->findAll();
        $interesteds = $this->interestedRepository->findAll();

        $currentInteresteds = $this->interestedRepository->findBy(['user_id' => $this->currentUser->getId()]);
        $currentParticipants = $this->participantRepository->findBy(['user_id' => $this->currentUser->getId()]);

        $interestedEvents = [];
        foreach ($currentInteresteds as $interested) {
            $event = $this->eventRepository->findOneBy(['id' => $interested->getEventId()]);
            $event->setDescription($this->truncate($event->getDescription(), 200, '...'));
            $interestedEvents[] = $event;
        }

        $participantEvents = [];
        foreach ($currentParticipants as $participant) {
            $event = $this->eventRepository->findOneBy(['id' => $participant->getEventId()]);
            $event->setDescription($this->truncate($event->getDescription(), 200, '...'));
            $participantEvents[] = $event;
        }

        return $this->twig->render('user/index.html.twig', [
            'currentUser' => $this->currentUser,
            'interestedEvents' => $interestedEvents,
            'participantEvents' => $participantEvents,
            'interesteds' => $interesteds,
            'participants' => $participants,
        ]);
    }

    #[Route('/user/validate/{id}', name: 'app_user_validate', httpMethod: ['GET'])]
    public function validate()
    {
        $token = substr($_SERVER['PATH_INFO'], strrpos($_SERVER['PATH_INFO'], "/") + 1);
        $mailManager = new MailManager();
        $mailManager->validationUser($token);

        $this->redirect('/user/login');

    }
}