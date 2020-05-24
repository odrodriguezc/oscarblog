<?php

namespace Email;

use PHPMailer\PHPMailer\PHPMailer;
use Email\Email;

class SendEmail extends PHPMailer
{


    public function __construct()
    {

        $this->IsSMTP();
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'ssl';
        $this->Host = 'smtp.gmail.com';
        $this->Port = 465;
        $this->Username = 'contact.oscarblog@gmail.com';
        $this->Password = 'HgdFL5nQ8Kxh';
        $this->IsHTML(true);
        $this->From = "contact.oscarblog@gmail.com";

        return $this;
    }


    public  function process(Email $email): string
    {

        $this->AddAddress($email->getTo());
        $this->Subject = $email->getSubject();
        $this->Body = $email->getBody();
        $this->setFrom($email->getFrom());

        if (!$this->Send()) {
            $response = "Erreur...";
        } else {
            $response = "Merci. Votre message a été bien envoyé.";
        }
        return $response;
    }
}
