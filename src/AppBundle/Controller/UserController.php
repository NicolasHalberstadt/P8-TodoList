<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserAdminType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
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
    public function createAction(Request $request)
    {
        $user = new User();
        $form = null;
        if ($this->getUser()) {
            $isAdmin = strpos($this->getUser()->getRole(), "ROLE_ADMIN") !== false;
            if ($isAdmin) {
                $form = $this->createForm(UserAdminType::class, $user);
            }
        } else {
            $form = $this->createForm(UserType::class, $user);
        }
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRole('ROLE_USER');
            
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
    public function editAction(User $user, Request $request)
    {
        $form = null;
        if ($this->getUser()) {
            $isAdmin = strpos($this->getUser()->getRole(), "ROLE_ADMIN") !== false;
            if ($user == $this->getUser()) {
                $form = $this->createForm(UserType::class, $user, ['user' => $user]);
            } elseif ($isAdmin) {
                $form = $this->createForm(UserAdminType::class, $user, ['user' => $user]);
            }
        }
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            /*dump($form->getData()->getPassword());
            exit;
            if ($request->get('password')) {
            
            }*/
         /*   $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);*/
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            
            return $this->redirectToRoute('user_list');
        }
        
        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
