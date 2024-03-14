<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

class TaskService
{


    /**
     * Summary of function __construct
     *
     * @param TaskRepository $taskRepository TaskRepository
     * @param UserService $userService UserService
     */
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly UserService $userService
    ) {

    }

    /**
     * Summary of getAllTasks
     *
     * @return array<Task>
     */
    public function getAllTasks(): array
    {

        return $this->taskRepository->findAll();

    }

    /**
     * Summary of saveTask
     *
     * @param Task $task Task
     *
     * @return Task|null
     */
    public function saveTask(Task $task): ?Task
    {

        return $this->taskRepository->saveTask($task);

    }

    /**
     * Summary of deleteTask
     *
     * @param Task $task Task
     *
     * @return void
     */
    public function deleteTask(Task $task): void
    {

        $this->taskRepository->deleteTask($task);

    }


}
