<?php 

namespace App\Service;
use App\Repository\UserRepository;
use App\Service\DB\EntityManager;
use App\Factory\EventFactory;
use App\Repository\EventRepository;
use App\Service\Mail\MailManager;
use App\Repository\ParticipantRepository;

class AlertManager
{

    public function alert($user)
    {
        $entityManager = new EntityManager;
        $eventRepository = new EventRepository;
        $participantRepository = new ParticipantRepository;

        $subject = "Attention !";
        $mailManager = new MailManager;
        $tomorrow = new \DateTime("tomorrow");
        $userId = $user->getId();
        $datas = $entityManager->executeRequest("SELECT event.startDate, event.name FROM participant INNER JOIN user ON $userId = participant.user_id INNER JOIN event ON event.id = participant.event_id WHERE user.id = $userId;");
        foreach($datas as $data)
        {
            if($tomorrow->diff(\DateTime::createFromFormat('Y-m-j H:i:s', $data['startDate']))->days == 0)
            {
                $body = $data['name'];
                $body .= "est l'évènement auquel vous vous êtes inscrit, et il se déroule demain ! Ne le loupez pas !";
                $mailManager->sendMail($user->getEmail(), $subject, $body);
            }
        }
    }

    public function alertj5($user)
    {
        $entityManager = new EntityManager;
        $eventRepository = new EventRepository;
        $participantRepository = new ParticipantRepository;
        $userRepository = new UserRepository;

        $userid = $user->getId();
        $datas = $eventRepository->executeRequest("SELECT event.name, event.id, event.startDate FROM event INNER JOIN user ON $userid = event.owner_id WHERE user.id = $userid;");
        $subject = "Attention !";
        $mailManager = new MailManager;
        $j5 = new \DateTime(" + 5 days ");
        foreach($datas as $data)
        {
            $eventid = $data['id'];
            $participantCount = $participantRepository->executeRequest("SELECT COUNT(*) AS participant_count FROM participant WHERE event_id = '$eventid'");
            if ($j5->diff(\DateTime::createFromFormat('Y-m-j H:i:s', $data['startDate']))->days == 0 && $participantCount[0]['participant_count'] == 0)
            {
                $body = $data['name'];
                $body .= " est l'évènement que vous avez créer, il commence dans 5 jours mais personne n'est inscrit...";
                $mailManager->sendMail($user->getEmail(), $subject, $body);
            }

        }
    }
}