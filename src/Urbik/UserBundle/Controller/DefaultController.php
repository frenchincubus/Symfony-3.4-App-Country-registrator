<?php

namespace Urbik\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Urbik\UserBundle\Entity\User;
use Urbik\UserBundle\Form\UserType;

class DefaultController extends Controller
{
    public function accueilAction()
    {
        return $this->render('@UrbikUser/Default/accueil.html.twig');
    }

    public function indexAction(Request $request)
    {
        $user = new User();
        $ip = $request->getClientIp();
       

        $form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('urbik_user_validate');

        }


        return $this->render('@UrbikUser/Default/index.html.twig', array(
            'form' => $form->createView(),
            'ip' => $ip
        ));
    }

    public function validateAction()
    {
        return $this->render('@UrbikUser/Default/valider.html.twig');
    }

    public function viewAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité à l\'administrateur.');
          }

        $users = $this->getDoctrine()->getManager()->getRepository('UrbikUserBundle:User')->sortedUsers();

        return $this->render('@UrbikUser/admin/admin.html.twig', array(
            'users' => $users,            
          ));
    }

    public function viewuserAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité à l\'administrateur.');
          }

        $user = $this->getDoctrine()->getManager()->getRepository('UrbikUserBundle:User')->find($id);

        return $this->render('@UrbikUser/admin/view.html.twig', array(
            'user' => $user
        ));
    }

    public function createAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité à l\'administrateur.');
          }

        $user = new User();
       

        $form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('urbik_user_admin');
        }

        return $this->render('@UrbikUser/Default/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité à l\'administrateur.');
          }

        $user = $this->getDoctrine()->getManager()->getRepository('UrbikUserBundle:User')->find($id);

        $form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('urbik_user_admin_view', array('id' => $id));

        }

        return $this->render('@UrbikUser/admin/edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    public function deleteAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité à l\'administrateur.');
          }
       
        $user = $this->getDoctrine()->getManager()->getRepository('UrbikUserBundle:User')->find($id);

        if (null === $user) {
            throw new NotFoundHttpException("L'inscrit n°".$id." n'existe pas.");
          }
      
          // On crée un formulaire vide, qui ne contiendra que le champ CSRF
          // Cela permet de protéger la suppression d'annonce contre cette faille
          $form = $this->get('form.factory')->create();
      
          if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
      
            $request->getSession()->getFlashBag()->add('info', "utilisateur retiré de la base de données");
      
             return $this->redirectToRoute('urbik_user_admin');
          }

         return $this->render('@UrbikUser/admin/delete.html.twig', array(
             'form' => $form->createView(),
             'user' => $user
         ));
    }

   
}
