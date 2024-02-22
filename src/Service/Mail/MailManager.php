<?php

namespace App\Service\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
}