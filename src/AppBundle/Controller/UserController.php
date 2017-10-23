<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
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
        return $this->render('AppBundle:User:login.html.twig');
    }

    public function registerAction(Request $request)
    {
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
            } else {
                $status = "There was an error in the registration, please try again later!!";
            }
            $this->session->getFlashBag()->add('status', $status);
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

}