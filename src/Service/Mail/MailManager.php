<?php

namespace App\Service\Mail;

use App\Repository\UserRepository;
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
            $phpmailer->send();

        } catch(Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'e-mail : ', $phpmailer->ErrorInfo;
        }

    }
        public function sendValidateMail($emailto)
        {
            $token = md5(uniqid(rand(), true));
            $userRepository = new UserRepository;
            if($userRepository->executeRequest("UPDATE user SET user.lastname = '321c9fb495648978b9f821a698bb6016' WHERE user.email = 'mathieu.moyaerts.pro@gmail.com'"))
            {

                $subject = "Confirmation de votre compte";
                $message = "Bonjour,\n\n";
                $message .= "Merci de vous être inscrit sur notre site.\n";
                $message .= "Veuillez cliquer sur le lien suivant pour activer votre compte :\n";
                $message .= "http://localhost:8000/user/validate/" . $token . "\n";
                $message .= "Cordialement,\nVotre BDE";

                $this->sendMail($emailto, $subject, $message);
            }
        }
        public function validationUser($token)
        {
            $userRepository = new UserRepository;
            $userForValidate = $userRepository->findOneBy(["token" => $token]);
            var_dump($userRepository->findOneBy(["token" => 'e8d9353740f56c2d39797aecdecc100d']));
            die();
            $userForValidate->setIsVerified(true);
            if($userRepository->update(["isVerified" => "1"], ["token" => $token]))
            {
                echo "Bienvenu ". $userForValidate->getFirstName();
            }
        }
}