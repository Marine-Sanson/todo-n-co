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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{


    /**
     * Summary of __construct
     *
     * @param TaskService $taskService TaskService
     */
    public function __construct(private readonly TaskService $taskService)
    {

    }

    /**
     * Summary of list
     *
     * @return Response
     */
    #[Route('/tasks', name: 'task_list')]
    public function list(): Response
    {

        return $this->render('task/list.html.twig', ['tasks' => $this->taskService->getAllTasks()]);

    }

    /**
     * Summary of create
     *
     * @param Request $request Request
     *
     * @return Response|RedirectResponse
     */
    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request): Response|RedirectResponse
    {

        $user = $this->getUser();

        if (isset($user)) {
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() === true && $form->isValid() === true) {
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

    /**
     * Summary of edit
     *
     * @param Task $task Task
     * @param Request $request Request
     *
     * @return Response|RedirectResponse
     */
    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    // #[IsGranted('TASK_EDIT', 'task', 'Seule la personne ayant créé une tache peut la modifier')]
    public function edit(Task $task, Request $request): Response|RedirectResponse
    {

        $this->denyAccessUnlessGranted('TASK_EDIT', $task, 'Seule la personne ayant créé une tache peut la modifier');
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() === true && $form->isValid() === true) {
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

    /**
     * Summary of taskIsDone
     *
     * @param Task $task Task
     *
     * @return RedirectResponse
     */
    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function taskIsDone(Task $task): RedirectResponse
    {

        $task->setIsDone(!$task->isDone());
        $this->taskService->saveTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');

    }

    /**
     * Summary of deleteTask
     *
     * @param Task $task Task
     *
     * @return RedirectResponse
     */
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    #[IsGranted('TASK_DELETE', 'task')]
    public function deleteTask(Task $task): RedirectResponse
    {

        $this->taskService->deleteTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');

    }


}
