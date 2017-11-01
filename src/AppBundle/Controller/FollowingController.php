<?php
/**
 * Created by PhpStorm.
 * User: bmaurino
 * Date: 10/24/17
 * Time: 10:31
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Following;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;

class FollowingController extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function followAction(Request $request)
    {
        $user = $this->getUser();
        $followerId = $request->get('followed');

        $em = $this->getDoctrine()->getManager();

        $userFollowed = $em->getRepository('BackendBundle:User')->find($followerId);

        $following = new Following();
        $following->setUserFollows($user);
        $following->setUserFollowed($userFollowed);

        $em->persist($following);

        $flush = $em->flush();

        if ($flush == null) {
            $status = 'User Follows';
        } else {
            $status = 'An error occurs, please try again later';
        }

        return new Response($status);
    }

    public function unfollowAction(Request $request)
    {
        $user = $this->getUser();
        $followerId = $request->get('followed');

        $em = $this->getDoctrine()->getManager();

        $followedUser = $em->getRepository('BackendBundle:Following')->findOneBy([
            'userFollows' => $user,
            'userFollowed' => $followerId
        ]);

        $em->remove($followedUser);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'User Unfollows';
        } else {
            $status = 'An error occurs, please try again later';
        }

        return new Response($status);
    }

    public function followingAction(Request $request, $nickname = null)
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

        $usersFollowing = $em->getRepository('BackendBundle:Following')->findUsersFollowing($userId);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($usersFollowing, $request->query->getInt('page', 1), 5);

        return $this->render('AppBundle:Following:following.html.twig', [
            'type' => 'following',
            'profileUser' => $user,
            'pagination' => $pagination
        ]);
    }

    public function followedAction(Request $request, $nickname = null)
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

        $usersFollowed = $em->getRepository('BackendBundle:Following')->findUsersFollowed($userId);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($usersFollowed, $request->query->getInt('page', 1), 5);

        return $this->render('AppBundle:Following:following.html.twig', [
            'type' => 'followed',
            'profileUser' => $user,
            'pagination' => $pagination
        ]);
    }
}