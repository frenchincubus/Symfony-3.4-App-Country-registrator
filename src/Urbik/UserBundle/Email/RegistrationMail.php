<?php

namespace Urbik\UserBundle\Email;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Urbik\UserBundle\Entity\User;

class RegistrationMail
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    public function __construct(\Swift_Mailer $mailer, Registry $doctrine)
    {
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
    }

    public function sendMail(User $user)
    {
        $admin = $this->doctrine->getEntityManager()->getRepository('UrbikAuthBundle:Admin')->findAll();

        $message = (new \Swift_Message('Confirmation d\'inscription '.$user->getNom().' '.$user->getPrenom()))
                    ->setFrom(['misterbluepearl@hotmail.com' => 'Urbik Admin'])
                    ->setTo($user->getMail())
                    ->setBcc($admin[0]->getEmail())
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