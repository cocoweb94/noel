<?php

// src/Tools/Mail.php
namespace App\Tools;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class Mail
{
    private $email;
    private $context;

    public function __construct($email, $context) {
        $this->email = $email;
        $this->context = $context;
    }

    public function sendContactMail(){
        $email = (new Email())
            ->from($this->email)
            ->to('contact@easycabservices.com')
            ->subject('Demande d\'informations Easy Cab Services')
            ->text("Demande d'informations : Nom et prénom : " .
                $this->context['nom'] . " " . $this->context['prenom'] . " Email : " .
                $this->context['email'] . " Message : " . $this->context['message'])
            ->html("<html><body><p>Demande d'informations :<br></p><p>Nom et pr&eacute;nom :<br>" .
                $this->context['nom'] . " " . $this->context['prenom'] . "</p><p>Email :<br>" .
                $this->context['email'] . "</p><p>M&eacute;ssage :<br>" . $this->context['message'] . "</p></body></html>");
        return $email;
    }

    public function sendReservationMail($nom, $prenom, $payerId, $distance, $temps){
        $date = date_format(new \DateTime('now'), 'd/m/Y H:i');
        $email = (new TemplatedEmail())
            ->from('contact@easycabservices.com')
            // TODO: CHANGE MAIL USER
            ->to($this->email)
            ->bcc('fcoelho92@hotmail.com')
            ->subject('Confirmation de commande Easy Cab Services du '. $date)

            // path of the Twig template to render
            ->htmlTemplate('emails/reservation.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'price' => $this->context,
                'nom' => $nom,
                'prenom' => $prenom,
                'payerId' => $payerId,
                'distance' => $distance,
                'temps' => $temps,
            ])
        ;
        return $email;
    }

    public function sendReservationDevisMail($nom, $prenom){
        $date = date_format(new \DateTime('now'), 'd/m/Y H:i');
        $email = (new TemplatedEmail())
            ->from('contact@easycabservices.com')
            ->to($this->email)
            ->bcc('contact@easycabservices.com')
            ->subject('Confirmation de demande de devis Easy Cab Services du '. $date)

            // path of the Twig template to render
            ->htmlTemplate('emails/reservationDevis.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'price' => $this->context,
                'nom' => $nom,
                'prenom' => $prenom
            ])
        ;
        return $email;
    }

}