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
                    ->setFrom(['kibru-966@outlook.fr' => 'Urbik Admin'])
                    ->setTo($user->getMail())
                    ->setBcc($admin[0]->getEmail())
                    ->setBody(
                        '<h3>Message de confirmation</h3>

                        <p>Hello '.$user->getNom().' '. $user->getPrenom().' , your account is successfully registered.</p><br>
                        <p>Your informations :</p><br>
                        
                        <p><strong>Birthdate</strong>: '.$user->getBirthdate()->format('d M Y').'</p>
                        <p><strong>Sex</strong>: '.$user->getSex().'</p>
                        <p><strong>Country</strong>: '.$user->getCountry().'</p>
                        <p><strong>Region</strong>: '.$user->getRegion().'</p>
                        <p><strong>Job</strong>: '.$user->getJob().'</p>
                        
                        <br><p>Thanks.</p>',
                        'text/html'
                    );

        $this->mailer->send($message);

    }
}