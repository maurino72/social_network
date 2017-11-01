<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PublicationType;
use Symfony\Component\HttpFoundation\Session\Session;

use BackendBundle\Entity\Like;
use BackendBundle\Entity\Publication;
use BackendBundle\Entity\User;

class LikeController extends Controller
{
    public function likeAction($id = null)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $publication = $em->getRepository('BackendBundle:Publication')->find($id);

        $like = new Like();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'You like this publication';
        } else {
            $status = 'There was an error, please try again later';
        }

        return new Response($status);
    }

    public function unlikeAction($id = null)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $publicationLiked = $em->getRepository('BackendBundle:Like')->findOneBy([
            'user' => $user,
            'publication' => $id,
        ]);

        $em->remove($publicationLiked);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'Publication unlike';
        } else {
            $status = 'There was an error on trying to unlike the publication';
        }

        return new Response($status);
    }


    public function publicationLikesAction(Request $request, $nickname = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($nickname != null) {
            $user = $em->getRepository('BackendBundle:User')->findOneBy([
                'nickname' => $nickname,
            ]);
        } else {
            $user = $this->getUser();
        }

        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home_publications'));
        }

        $userId = $user->getId();

        $publicationLikes = $em->getRepository('BackendBundle:Like')->findPublicationLikesByUser($userId);

        $paginator = $this->get('knp_paginator');
        $likes = $paginator->paginate($publicationLikes, $request->query->getInt('page', 1), 5);

        return $this->render('AppBundle:Like:likes.html.twig', [
            'user' => $user,
            'pagination' => $likes
        ]);
    }
}