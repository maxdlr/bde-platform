<?php

namespace App\Service\Mail;

use App\Repository\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Entity\User;
class MailManager
{
    public function getPhpMailer(): PHPMailer
    {
    // Config PHP Mailer
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

            $err = $phpmailer->send();
        } catch(Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'e-mail : ', $phpmailer->ErrorInfo;
        }

    }
    /** @var User */
    public function sendMailParticipant($user, $event)
    {
        $startDate = $event->getStartDate();
        $strStartDate = $startDate->format("Y-m-d H:i:s");
        $emailto = $user->getEmail();
        $subject = "Confirmation de votre inscription";
        $message = "Bonjour ". $user->getFirstName() . " " . $user->getLastName() . ",\n\n";
        $message .= "Nous sommes ravis de vous compter parmi les participants inscrits à notre prochain événement, ". $event->getName() . "";
        $message .= "Date : ".$strStartDate . "\n";
        $message .= "Votre inscription a été enregistrée avec succès et nous sommes impatients de vous accueillir pour partager cette expérience enrichissante.\n\n";
        $message .= "Cordialement,\nVotre équipe d'organisation";
        $this->sendMail($emailto, $subject, $message);
    }
}