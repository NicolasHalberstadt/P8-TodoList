<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class TaskController
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/todo", name="task_todo_list")
     */
    public function listAction()
    {
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findBy(['isDone' => false])]
        );
    }
    
    /**
     * @Route("/tasks/done", name="task_done_list")
     */
    public function doneListAction()
    {
        return $this->render(
            'task/list.html.twig',
            ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findBy(['isDone' => true])]
        );
    }
    
    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();
            
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            
            return $this->redirectToRoute('task_todo_list');
        }
        
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            
            return $this->redirectToRoute('task_todo_list');
        }
        
        return $this->render(
            'task/edit.html.twig',
            [
                'form' => $form->createView(),
                'task' => $task,
            ]
        );
    }
    
    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();
        
        if (!$task->isDone()) {
            $this->addFlash('success', sprintf('La tâche \'%s\' a bien été marquée comme a faire.', $task->getTitle()));
            
            return $this->redirectToRoute('task_done_list');
        } else {
            $this->addFlash('success', sprintf('La tâche \'%s\' a bien été marquée comme faite.', $task->getTitle()));
            
            return $this->redirectToRoute('task_todo_list');
        }
    }
    
    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if (($this->getUser() == $task->getUser()) || $this->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
            
            return $this->redirectToRoute('task_todo_list');
        }
        throw new UnauthorizedHttpException('Vous navez pas les droits pour supprimer cette tâche.');
    }
}
