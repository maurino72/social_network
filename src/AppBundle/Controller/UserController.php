<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use AppBundle\Form\UserType;
use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function loginAction(Request $request)
    {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        $authenticationError = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUserName();
        return $this->render('AppBundle:User:login.html.twig', [
            'username' => $lastUserName,
            'error' => $authenticationError
        ]);
    }

    public function registerAction(Request $request)
    {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nickname = :nickname')
                    ->setParameter('email', $form->get('email')->getData())
                    ->setParameter('nickname', $form->get('nickname')->getData());

                $userExists = $query->getResult();
                if (count($userExists) == 0) {
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $passwordEncoder = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());

                    $user->setPassword($passwordEncoder);
                    $user->setRole('ROLE_USER');
                    $user->setImage(null);

                    $em->persist($user);
                    $em->flush();

                    // TODO enviar notificacion via email
                    $status = "Registration Complete";

                    $this->session->getFlashBag()->add('status', $status);

                    return $this->redirect('login');
                } else {
                    $status = "The user allready exists!";
                }
                $this->session->getFlashBag()->add('status', $status);
            }


        }

        return $this->render('AppBundle:User:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function nicknameCheckAction(Request $request)
    {
        $nickname = $request->get('nickname');
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('BackendBundle:User')->findOneBy(['nickname' => $nickname]);

        $result = "Used";
        if (count($user) >= 1 && is_object($user)) {
            $result = "Used";
        } else {
            $result = 'Unused';
        }

        return new Response($result);
    }

    public function editUserAction(Request $request)
    {
        $user = $this->getUser();
        $oldProfileImage = $user->getImage();
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted()) {
            if ($userForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //TODO migrar a un repositorio
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nickname = :nickname')
                    ->setParameter('email', $userForm->get('email')->getData())
                    ->setParameter('nickname', $userForm->get('nickname')->getData());

                $userExists = $query->getResult();

                if (count($userExists) == 0 || $user->getEmail() == $userExists[0]->getEmail() && $user->getNickname() == $userExists[0]->getNickname()) {

                    // Upload file
                    $imageFile = $userForm->get('image')->getData();
                    if (!empty($imageFile) && $imageFile != null) {
                        $extension = $imageFile->guessExtension();
                        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg') {
                            $filename = $user->getId() . time() . "." . $extension;
                            $imageFile->move("uploads/users", $filename);

                            $user->setImage($filename);
                        }
                    } else {
                        $user->setImage($oldProfileImage);
                    }

//                    $user->setPassword($passwordEncoder);
                    $user->setBiografy($userForm->get('biografy')->getData());

                    $em->persist($user);
                    $em->flush();

                    $status = "Profile Updated";
                } else {
                    $status = "The user allready exists!";
                }
            } else {
                $status = "There was an error, please try again later!!";
            }
            $this->session->getFlashBag()->add('status', $status);
            return $this->redirect('profile-settings');
        }
        return $this->render('AppBundle:User:profile_settings.html.twig', [
            'form' => $userForm->createView(),
        ]);
    }

    public function usersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 15);

        return $this->render('AppBundle:User:people.html.twig', [
            'users' => $pagination
        ]);
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $search = $request->get('search', null);
        if ($search == null) {
            return $this->redirect($this->generateUrl('home_publications'));
        }

        $dql = "SELECT u FROM BackendBundle:User u 
                WHERE u.firstname LIKE :search OR 
                u.lastname LIKE :search OR u.nickname LIKE :search";
        $query = $em->createQuery($dql)->setParameter('search', "%$search%");

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 15);

        return $this->render('AppBundle:User:people.html.twig', [
            'users' => $pagination
        ]);
    }
}