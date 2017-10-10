<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegisterType;
use BackendBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

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
//                $user = $em->getRepository('BackendBundle:User');
                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nickname = :nickname')
                    ->setParameter('email', $form->get('email')->getData())
                    ->setParameter('nickname', $form->get('nickname')->getData());

                $userExists = $query->getResult();
//                $userNickOrEmail = $em->getRepository('BackendBundle:UserRepository')->findByEmailOrNickname($form->get('email')->getData(), $form->get('nickname')->getData());
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
                    $this->addFlash('notice', 'Registration complete!');

                    return $this->redirect('login');
                } else {
                    $registrationStatus = "The user allready exists!";
                }
            } else {
                $registrationStatus = "There was an error in the registration, please try again later!!";
            }
        }

        return $this->render('AppBundle:User:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}