<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SharedResource;
use AppBundle\Form\SharedResourceType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shared_resources")
 */
class SharedResourceController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(EntityManagerInterface $entityManager)
    {
        /** @var SharedResource[] $sharedResources */
        $sharedResources = $entityManager->getRepository('AppBundle:SharedResource')->findAll();

        return $this->render('shared_resource/index.html.twig', [
            'sharedResources' => $sharedResources
        ]);
    }

    /**
     * @Route("/new")
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request, EntityManagerInterface $entityManager)
    {
        $sharedResource = new SharedResource();
        $user = $this->getUser();
        $sharedResource->setAuthor($this->getUser()->getEmail());
        $form = $this->createSharedResourceForm($sharedResource);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager->persist($sharedResource);
                $entityManager->flush();

                $request->getSession()->getFlashBag()->add('success', 'Oh yeah ! That is excellent 👌');

                return $this->redirectToRoute('app_sharedresource_index');
            }
        }

        return $this->render('shared_resource/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function editAction(SharedResource $sharedResource)
    {
        $form = $this->createSharedResourceForm($sharedResource);

        return $this->render('shared_resource/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @param SharedResource $sharedResource
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createSharedResourceForm(SharedResource $sharedResource)
    {
        $form = $this->createForm(SharedResourceType::class, $sharedResource, [
            'method' => Request::METHOD_POST,
        ]);

        return $form;
    }

}
