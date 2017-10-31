<?php

namespace AppBundle\Controller;

use BackendBundle\Entity\Publication;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PublicationType;
use Symfony\Component\HttpFoundation\Session\Session;

class PublicationController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication();

        $form = $this->createForm(PublicationType::class, $publication);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
             if ($form->isValid()) {

                 // Upload Image
                 $file = $form['image']->getData();
                 if (!empty($file) && $file != null) {
                     $extension = $file->guessExtension();
                     if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
                         $fileName = $user->getid() . time() . $extension;
                         $file->move('uploads/publications/images', $fileName);
                         $publication->setImage($fileName);
                     } else {
                         $publication->setImage(null);
                     }
                 }else {
                     $publication->setImage(null);
                 }
                 // Upload Document
                 $document = $form['document']->getData();
                 if (!empty($document) && $document != null) {
                     $extension = $document->guessExtension();
                     if ($extension == 'pdf') {
                         $fileName = $user->getid() . time() .'.'. $extension;
                         $document->move('uploads/publications/documents', $fileName);
                         $publication->setDocument($fileName);
                     } else {
                         $publication->setDocument(null);
                     }
                 }else {
                     $publication->setDocument(null);
                 }

                 $publication->setUser($user);
                 $publication->setCreatedAt(new \DateTime('now'));

                 $em->persist($publication);
                 $flush = $em->flush();

                 if ($flush == null) {
                     $status = 'The Publication has been created successfully';
                 } else {
                     $status = 'An error occurs on creating the publication, please try again later';
                 }
             }else {
                $status = 'An error occurs on posting the publication, please try again later';
             }

             $this->session->getFlashBag()->add('status',$status);
             $this->redirectToRoute('home_publication');
        }

        $publications = $this->getPublications($request);

        return $this->render('AppBundle:Publication:home.html.twig', [
            'form' => $form->createView(),
            'publications' => $publications
        ]);
    }

    public function getPublications(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $following = $em->getRepository('BackendBundle:Following')->findBy([
            'userFollows' => $user
        ]);

        $allUsersFollowing = [];

        foreach ($following as $follow) {
            $allUsersFollowing[] = $follow->getUserFollowed();
        }

        $publications = $em->getRepository('BackendBundle:Publication')->findAllPublicationsFromUsersFollowed($user->getId(), $allUsersFollowing);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($publications, $request->query->getInt('page', 1), 5);

        return $pagination;
    }

}