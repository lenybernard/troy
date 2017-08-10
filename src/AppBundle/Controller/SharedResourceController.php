<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SharedResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/shared_resources")
 */
class SharedResourceController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction(EntityManagerInterface $entityManager)
    {
        /** @var SharedResource[] $sharedResources */
        $sharedResources = $entityManager->getRepository('AppBundle:SharedResource')->findAll();

        return $this->render('shared_resource/index.html.twig', [
            'sharedResources' => $sharedResources
        ]);
    }

}
