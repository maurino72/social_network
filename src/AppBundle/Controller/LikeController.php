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

}