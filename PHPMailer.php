<?php

namespace PHPMailer\PHPMailer;

class PHPMailer
{
    public $isSMTP = false;
    public $Host;
    public $SMTPAuth;
    public $Username;
    public $Password;
    public $SMTPSecure;
    public $Port;
    public $From;
    public $FromName;
    public $Subject;
    public $Body;
    public $AltBody;
    public $CharSet = 'UTF-8';
    public $isHTML = false;
    private $to = [];

    public function isSMTP()
    {
        $this->isSMTP = true;
    }

    public function setFrom($address, $name = '')
    {
        $this->From = $address;
        $this->FromName = $name;
    }

    public function addAddress($address)
    {
        $this->to[] = $address;
    }

    public function send()
    {
        $headers = "From: {$this->FromName} <{$this->From}>\r\n";
        $headers .= "Reply-To: {$this->From}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        foreach ($this->to as $recipient) {
            if (!mail($recipient, $this->Subject, $this->Body, $headers)) {
                throw new Exception("Erreur d'envoi");
            }
        }

        return true;
    }
}
