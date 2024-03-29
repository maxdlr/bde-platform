<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\InterestedRepository;
use App\Repository\ParticipantRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Service\Mail\MailManager;
use DateTime;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminEventController extends AbstractController
{
    private User|null|bool $currentUser;

    public function __construct(
        Environment                            $twig,
        private readonly EventRepository       $eventRepository,
        private readonly TagRepository         $tagRepository,
        private readonly UserRepository        $userRepository,
        private readonly ParticipantRepository $participantRepository,
        private readonly InterestedRepository  $interestedRepository,
    )
    {
        parent::__construct($twig);
        $this->currentUser = $this->getUserConnected();

        $this->redirectIfForbidden();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route('/admin/event/index', name: 'app_admin_event_index', httpMethod: ['GET', 'POST'])]
    public function index(): string
    {
        $eventObjects = $this->eventRepository->findAll();
        array_map(fn(Event $event): Event => $event->setDescription($this->truncate($event->getDescription(), 40, '...')), $eventObjects);
        $events = array_map(fn(Event $event): array => $event->toArray(), $eventObjects);

        $participants = $this->participantRepository->findAll();
        $interesteds = $this->interestedRepository->findAll();
        $users = $this->userRepository->findAll();

        $eventsWithOwners = [];
        foreach ($events as $event) {

            $eventsWithOwners[] = [
                ...$event,
                'owner' => $this->userRepository->findOneBy(
                        ['id' => $event['owner_id']]
                    )->getFirstname()
                    . ' '
                    . $this->userRepository->findOneBy(
                        ['id' => $event['owner_id']]
                    )->getLastname()
            ];
        }

        return $this->twig->render('admin/index.html.twig', [
            'items' => $eventsWithOwners,
            'entityName' => 'event',
            'interesteds' => $interesteds,
            'participants' => $participants,
            'events' => $eventObjects,
            'users' => $users,
            'currentUser' => $this->currentUser
        ]);
    }

    #[Route('/admin/event/new', name: 'app_admin_event_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        $this->clearFlashs();
        $tags = $this->tagRepository->findAll();

        array_map('trim', $_POST);

        if (isset($_POST['new-event-submit']) && $_POST['new-event-submit'] == 'new-event') {
            $event = new Event();
            $eventRepository = new EventRepository();


            $validData = true;

            if (intval($_POST['capacity']) < 1) {
                $validData = false;
                $this->addFlash("danger", "La capacité de l'évènement doit être de 1 au minimum");
            }

            $nowDate = new DateTime('now');

            $startDate = new DateTime($_POST['startDate']);
            if ($startDate < $nowDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de début est antérieure à la date actuelle");
            }

            $endDate = new DateTime($_POST['endDate']);
            if ($endDate < $nowDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de fin est antérieure à la date actuelle");
            }

            if ($endDate < $startDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de fin est antérieure à la date de début");
            }


            if (isset($_FILES['new-event-file']) && $_FILES['new-event-file']['error'] === UPLOAD_ERR_OK) {

                $originalFileName = $_FILES['new-event-file']['name'];
                $tmpFileName = $_FILES['new-event-file']['tmp_name'];

                $fileNameCmps = explode(".", $originalFileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $fileSize = $_FILES['new-event-file']['size'];

                $newFileName = md5(time() . $tmpFileName) . '.' . $fileExtension;
                $allowedfileExtensions = array('jpg', 'jpeg', 'png');

                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = __DIR__ . '/../../../public/assets/images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    move_uploaded_file($tmpFileName, $dest_path);
                }
            }


            if ($validData === false) {
                return $this->twig->render('admin/new/event-new.html.twig', [
                    'tags' => $tags,
                    'flashbag' => $_SESSION["flashbag"]
                ]);
            } else {
                $currentUser = $this->getUserConnected();
                $currentUserId = $currentUser->getId();

                $event
                    ->setName($_POST['name'])
                    ->setDescription($_POST['description'])
                    ->setStartDate(new DateTime($_POST['startDate']))
                    ->setEndDate(new DateTime($_POST['endDate']))
                    ->setTag($_POST['tag'])
                    ->setCapacity($_POST['capacity'])
                    ->setOwnerId($currentUserId);

                if (isset($newFileName) && isset($fileSize)) {
                    $event
                        ->setFileName($newFileName)
                        ->setFileSize($fileSize);
                } else {
                    $event
                        ->setFileName("")
                        ->setFileSize(0);
                }

                if ($eventRepository->insertOne($event)) {
                    $this->redirect('/admin/event/index');
                }
            }
        }

        return $this->twig->render('admin/new/event-new.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/admin/event/edit/{id}', name: 'app_admin_event_edit', httpMethod: ['GET', 'POST'])]
    public function edit(int $idEvent): string
    {
        $eventRepository = new EventRepository();
        $event = $eventRepository->findOneBy(['id' => $idEvent]);

        $tags = $this->tagRepository->findAll();

        if (isset($_POST['edit-event-submit']) && $_POST['edit-event-submit'] == 'edit-event') {

            $validData = true;

            if (intval($_POST['capacity']) < 1) {
                $validData = false;
                $this->addFlash("danger", "La capacité de l'évènement doit être de 1 au minimum");
            }

            $nowDate = new DateTime('now');

            $startDate = new DateTime($_POST['startDate']);
            if ($startDate < $nowDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de début est antérieure à la date actuelle");
            }

            $endDate = new DateTime($_POST['endDate']);
            if ($endDate < $nowDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de fin est antérieure à la date actuelle");
            }

            if ($endDate < $startDate) {
                $validData = false;
                $this->addFlash("danger", "Votre date de fin est antérieure à la date de début");
            }

            if ($validData === false) {
                return $this->twig->render('admin/edit/event-edit.html.twig', [
                    'item' => $event,
                    'tags' => $tags,
                    'flashbag' => $_SESSION["flashbag"]
                ]);
            } else {
                $updatedEventArray = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'startDate' => $_POST['startDate'],
                    'endDate' => $_POST['endDate'],
                    'tag' => $_POST['tag'],
                    'capacity' => $_POST['capacity']
                ];
                if ($event->getStartDate() != $startDate) {
                    $mailManager = new MailManager;
                    $mailManager->sendModifDate($event, $startDate);
                }

                if ($eventRepository->update($updatedEventArray, $event)) {
                    $this->redirect('/admin/event/index');
                }
            }
        } elseif (!is_null($event)) {
            return $this->twig->render('admin/edit/event-edit.html.twig', [
                'item' => $event,
                'tags' => $tags,
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }

    #[Route('/admin/event/delete/{id}', name: 'app_admin_event_delete', httpMethod: ['POST'])]
    public function delete(int $idEvent): void
    {
        $eventRepository = new EventRepository();
        $eventToDelete = $eventRepository->findOneBy(['id' => $idEvent]);

        if ($eventRepository->delete($eventToDelete)) {
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }
}