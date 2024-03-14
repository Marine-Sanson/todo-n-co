<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

class TaskService
{


    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly UserService $userService
    ){

    }

    public function getAllTasks(): array
    {

        return $this->taskRepository->findAll();

    }

    public function saveTask(Task $task): ?Task
    {

        return $this->taskRepository->saveTask($task);

    }

    public function deleteTask(Task $task): void
    {

        $this->taskRepository->deleteTask($task);

    }


}
