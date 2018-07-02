<?php

namespace Urbik\UserBundle\Email;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Urbik\UserBundle\Email\RegistrationMail;
use Urbik\UserBundle\Entity\User;

class RegistrationListener
{
    /**
     * @var RegistrationMail
     */

    private $registrationMail;

    public function __construct(RegistrationMAil $registrationMail)
    {
        $this->registrationMail = $registrationMail;
    }

    public function PostPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof User) {
            return;
        }

        $this->registrationMail->sendMail($entity);
    }
}