<?php

namespace Urbik\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Urbik\AuthBundle\Entity\Admin;
use Urbik\AuthBundle\Form\AdminType;

class SecurityController extends Controller
{

    /*
    *   Function login, for admin board access
    */
    public function loginAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('urbik_user_admin');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@UrbikAuth/Security/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /*
    *   Function called on a new use of the app - Mail notification use a valid admin mail  
    */
    public function AdminMailAction(Request $request)
    {
        $admin = new Admin();

        $form = $this->get('form.factory')->create(AdminType::class, $admin);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('urbik_user_admin');
        }

        return $this->render('@UrbikAuth/Security/adminMail.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /*
    *  Function for modifying the admin mail 
    */
    public function EditMailAction(Request $request)
    {
        $admin = $this->getDoctrine()->getManager()->getRepository('UrbikAuthBundle:Admin')->findAll();

        $form = $this->get('form.factory')->create(AdminType::class, $admin[0]);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('urbik_user_admin');
        }

        return $this->render('@UrbikAuth/Security/editMail.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}