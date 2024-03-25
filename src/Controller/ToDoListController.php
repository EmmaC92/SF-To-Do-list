<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="app_to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();

        return $this->render('index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/create", name="app_to_do_list_create")
     */
    public function create(Request $request): Response
    {
        $title = trim($request->request->get('title'));

        if (empty($title)) {
            return $this->redirectToRoute('app_to_do_list');
        }

        $doctrineManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title)->setStatus(false);
        $doctrineManager->persist($task);
        $doctrineManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    /**
     * @Route("/switch-status/{id}", name="app_to_do_list_switch")
     */
    public function swithStatus(int $id): Response
    {
        $doctrineManager = $this->getDoctrine()->getManager();

        $task = $doctrineManager->getRepository(Task::class)->find($id);

        $task->setStatus(
            !($task->getStatus())
        );

        $doctrineManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="app_to_do_list_delete", methods={"GET"})
     */
    public function delete(int $id): Response
    {
        $doctrineManager = $this->getDoctrine()->getManager();

        $task = $doctrineManager->getRepository(Task::class)->find($id);

        $doctrineManager->remove($task);

        $doctrineManager->flush();

        return $this->redirectToRoute('app_to_do_list');
    }
}
