<?php

namespace Urbik\UserBundle\Email;

use Urbik\UserBundle\Entity\User;

class RegistrationMail
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;



    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(User $user)
    {
        $message = (new \Swift_Message('Confirmation d\'inscription '.$user->getNom().' '.$user->getPrenom()))
                    ->setFrom('misterbluepearl@hotmail.com')
                    ->setTo($user->getMail())
                    ->setBcc('misterbluepearl@hotmail.com')
                    ->setBody(
                        '<h3>Message de confirmation</h3>

                        <p>Hello '.$user->getNom().' '. $user->getPrenom().' , your account is successfully registered.</p>
                        <p>Your informations :</p> 
                        
                        <p>'.$user->getBirthdate()->format('d M Y').'</p>
                        <p>'.$user->getSex().'</p>
                        <p>'.$user->getCountry().'</p>
                        <p>'.$user->getRegion().'</p>
                        <p>'.$user->getJob().'</p>
                        
                        <p>Thanks.</p>',
                        'text/html'
                    );

        $this->mailer->send($message);

    }
}