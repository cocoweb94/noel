<?php

// src/Tools/Mail.php
namespace App\Tools;


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
            ->to('contact@diaconat-grenoble.org')
            ->subject('Demande d\'informations Easy Cab Services')
            ->text("Demande d'informations : Nom et prénom : " .
                $this->context['nom'] . " " . $this->context['prenom'] . " Email : " .
                $this->context['email'] . " Message : " . $this->context['message'])
            ->html("<html><body><p>Demande d'informations :<br></p><p>Nom et pr&eacute;nom :<br>" .
                $this->context['nom'] . " " . $this->context['prenom'] . "</p><p>Email :<br>" .
                $this->context['email'] . "</p><p>M&eacute;ssage :<br>" . $this->context['message'] . "</p></body></html>");
        return $email;
    }

    public function sendReservationMail($nom, $prenom, $orderId, $livraison, $mailer){
        $date = date_format(new \DateTime('now'), 'd/m/Y H:i');
        $message = (new \Swift_Message('Confirmation de commande Marché de noël du '. $date))
            ->setFrom('contact@diaconat-grenoble.org')
            ->setTo($this->email)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'emails/reservation.html.twig',
                    [
                        'price' => $this->context,
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'payerId' => $orderId,
                        'livraison' => $livraison,
                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
    }

}