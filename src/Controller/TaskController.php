<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use DateTimeImmutable;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{

    public function __construct(private readonly TaskService $taskService)
    {}

    #[Route('/tasks', name: 'task_list')]
    public function list(): Response
    {

        return $this->render('task/list.html.twig', ['tasks' => $this->taskService->getAllTasks()]);

    }

    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request): Response|RedirectResponse
    {

        $user = $this->getUser();

        if ($user) {
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $task->setCreatedAt(new DateTimeImmutable());
                $task->setUser($user);
                $this->taskService->saveTask($task);

                $this->addFlash('success', 'La tâche a été bien été ajoutée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/create.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirectToRoute('app_login');

    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function edit(Task $task, Request $request): Response|RedirectResponse
    {

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->saveTask($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]
        );

    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function taskIsDone(Task $task): RedirectResponse
    {

        $task->setIsDone(!$task->isDone());
        $this->taskService->saveTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');

    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTask(Task $task): RedirectResponse
    {

        $this->taskService->deleteTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');

    }

}
