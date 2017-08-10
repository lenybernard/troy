<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoderSubscriber implements EventSubscriber
{
    /**
     * @var PasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->encodePassword($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encodePassword($args);
    }

    /**
     * Encode password thanks to plain password
     * @param LifecycleEventArgs $args
     */
    public function encodePassword(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User && null !== $entity->getPlainPassword()) {
            $entity->setPassword(
                $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword())
            );
        }
    }
}