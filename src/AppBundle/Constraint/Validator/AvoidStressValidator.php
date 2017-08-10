<?php

namespace AppBundle\Constraint\Validator;


use AppBundle\Entity\SharedResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AvoidStressValidator extends ConstraintValidator

{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AvoidStressValidator constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param SharedResource $value
     * @param Constraint     $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $tenSecondsAgo = new \DateTime('-10 seconds');
        $entries = $this->entityManager->getRepository('AppBundle:SharedResource')
            ->createQueryBuilder('sr')
            ->where('sr.createdAt >= :tenSecondsAgo')
            ->setParameter('tenSecondsAgo', $tenSecondsAgo->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        if (count($entries)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value->getTitle())
                ->addViolation();
        }
    }
}