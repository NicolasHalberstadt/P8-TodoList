<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserAdminType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class UserController
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package AppBundle\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        return $this->render(
            'user/list.html.twig',
            ['users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()]
        );
    }
    
    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, PasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            
            return $this->redirectToRoute('user_list');
        }
        
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, PasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            
            return $this->redirectToRoute('user_list');
        }
        
        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
    
    /**
     * @Route("/users/{id}/delete", name="user_delete")
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        
        $this->addFlash('success', "L'utilisateur a bien ete supprimé");
        
        return $this->redirectToRoute('user_list');
    }
}
