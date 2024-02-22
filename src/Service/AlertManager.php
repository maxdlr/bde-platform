<?php 

namespace App\Service;
use App\Factory\ParticipantFactory;
use App\Repository\UserRepository;
use App\Service\DB\EntityManager;
use App\Factory\EventFactory;
use App\Repository\EventRepository;
use App\Service\Mail\MailManager;
use App\Repository\ParticipantRepository;

class AlertManager {

public function alert()
    {
        $entityManager = new EntityManager;
        $eventRepository = new EventRepository;
        $participantRepository = new ParticipantRepository;

        // $event = EventFactory::make()->withName('Sortie à la plage ')->withStartDate(new \DateTime("tomorrow"))
        // ->withEndDate(new \DateTime("2024-02-24"))->generate();
        // $eventRepository->insertOne($event);

        // $retrievedEvent = $eventRepository->findOneBy(['name' => 'Sortie à la plage ']);

        // $participant = ParticipantFactory::make()->withEvent($retrievedEvent)->generate();
        // $participantRepository->insertOne($participant);
        $subject = "Attention !";
        $mailManager = new MailManager;
        $tomorrow = new \DateTime("tomorrow");
        $datas = $entityManager->executeRequest("SELECT user.email, event.startDate, event.name FROM participant INNER JOIN user ON user.id = participant.user_id INNER JOIN event ON event.id = participant.event_id;");
        foreach($datas as $data)
        {
            if($tomorrow->diff(\DateTime::createFromFormat('Y-m-j H:i:s', $data['startDate']))->days == 0)
            {
                $body = $data['name'];
                $body .= "est l'évènement auquel vous vous êtes inscrit, et il se déroule demain ! Ne le loupez pas !";
                $mailManager->sendMail($data['email'], $subject, $body);
            }
        }

    }

    public function alertj5()
    {
        $entityManager = new EntityManager;
        $eventRepository = new EventRepository;
        $participantRepository = new ParticipantRepository;
        $userRepository = new UserRepository;

        $user = $userRepository->findOneBy(['lastname' => 'MOYAERTS']);

        $event = EventFactory::make()->withName('Sortie à la plage ')->withStartDate(new \DateTime("2024-02-27"))
        ->withEndDate(new \DateTime("2024-02-24"))->withOwner($user)->generate();
        $eventRepository->insertOne($event);

        $subject = "Attention !";
        $mailManager = new MailManager;
        $j5 = new \DateTime(" + 5 days ");
        $datas = $entityManager->executeRequest("SELECT user.email, event.startDate, event.name FROM event INNER JOIN user ON event.owner_id = user.id WHERE event.owner_id = user.id;");
        foreach($datas as $data)
        {
            if($j5->diff(\DateTime::createFromFormat('Y-m-j H:i:s', $data['startDate']))->days == 5)
            {
                $body = $data['name'];
                $body .= "est l'évènement que vous avez créer, il commence dans 5 jours mais personne n'est inscrit...";
                $mailManager->sendMail("mathieu.moyaerts.pro@gmai.com", $subject, $body);
            }
            else {
                var_dump($j5);
            }
        }

    }
}