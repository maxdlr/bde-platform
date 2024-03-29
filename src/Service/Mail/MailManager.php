<?php

namespace App\Service\Mail;

use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailManager
{
    public function getPhpMailer(): PHPMailer
    {
        $phpmailer = new PHPMailer(true);

        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = 'mathieu.moyaerts.pro@gmail.com';
        $phpmailer->Password = 'evhw tfsv lvxn mdfo';
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Port = 587;

        return $phpmailer;
    }

    public function sendMail($emailTo, $subject, $body)
    {

        // Envoyer un e-mail
        $phpmailer = $this->getPhpMailer();
        try{
            $phpmailer->setFrom($phpmailer->Username);
            $phpmailer->addAddress($emailTo);
            $phpmailer->isHTML(true);
            $phpmailer->Subject = $subject;
            $phpmailer->Body = $body;
            $phpmailer->send();

        } catch(Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'e-mail : ', $phpmailer->ErrorInfo;
        }

    }


    public function sendMailParticipant($user, $event)
    {
        $startDate = $event->getStartDate();
        $strStartDate = $startDate->format("Y-m-d H:i:s");
        $emailto = $user->getEmail();
        $subject = "Confirmation de votre inscription ";
        $message = "Bonjour ". $user->getFirstName() . " " . $user->getLastName() . ",\n\n ";
        $message .= "Nous sommes ravis de vous compter parmi les participants inscrits à notre prochain événement, ". $event->getName() . " ";
        $message .= "Date : ".$strStartDate . "\n  ";
        $message .= "Votre inscription a été enregistrée avec succès et nous sommes impatients de vous accueillir pour partager cette expérience enrichissante.\n\n ";
        $message .= "Cordialement,\nVotre équipe d'organisation du BDE";
        $this->sendMail($emailto, $subject, $message);
    }

        public function sendValidateMail($emailto, $token)
        {
            $userRepository = new UserRepository;
            if($userRepository->executeRequest("UPDATE user SET token = '$token' WHERE email = '$emailto'"))

                $subject = "Confirmation de votre compte";
                $message = "Bonjour,\n\n ";
                $message .= "Merci de vous être inscrit sur notre site.\n ";
                $message .= "Veuillez cliquer sur le lien suivant pour activer votre compte :\n ";
                $message .= "http://localhost:8000/user/validate/" . $token . "\n ";
                $message .= "Cordialement,\n Votre BDE";

                $this->sendMail($emailto, $subject, $message);
        }
        public function validationUser($token)
        {
            $userRepository = new UserRepository;
            $userForValidate = $userRepository->findOneBy(["token"=> $token]);
            $userForValidate->setIsVerified(true);
            if($userRepository->update(["isVerified" => "1"], ["token" => $token]))
            {
                $this->sendMailToAdmin($userForValidate);
            }

 
        }

        public function sendModifDate($event, $startDate)
        {
            $strStartDate = $startDate->format("Y-m-d H:i:s");

            $subject = "Changement de date !";
            $message = "Bonjour,\n\n" .
            "Nous tenions à vous informer qu'il y a eu un changement de date pour l'événement \"" . $event->getName() . "\". La nouvelle date est le ".$strStartDate ." Veuillez noter ce changement dans votre agenda.\n\n
            Nous vous prions de nous excuser pour tout inconvénient que cela pourrait causer.\n\n".
            "Cordialement,\n" .
            "L'équipe d'organisation du BDE";
            $participantRepository = new ParticipantRepository;
            $participants = $participantRepository->findBy(["event_id" => $event->getId()]);
            $userRepository = new UserRepository;
            foreach ($participants as $participant)
            {
                $userId = $participant->getUserId();
                $user = $userRepository->findOneBy(["id" => $userId]);
                $email = $user->getEmail();

                $this->sendMail($email, $subject, $message);
            }
        }

        public function sendMailToAdmin($user)
        {
            $subject = "Nouvel utilisateur inscrit";
            $message = "Bonjour,

            Un nouvel utilisateur s'est inscrit :

            Nom : ".$user->getFirstName() . " " . $user->getLastName() ." 
            Adresse e-mail : ". $user->getEmail() . "

            Cordialement,
            L'administration du BDE";

            $userRepository = new UserRepository;
            $admins = $userRepository->findBy(["roles" => "admin"]);
            foreach ($admins as $admin)
            {
                $email = $admin->getEmail();
                $this->sendMail($email, $subject, $message);
            }
        }

}