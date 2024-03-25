<?php

namespace App\Tests\Service;

use App\Entity\Task;
use DateTimeImmutable;
use App\Service\TaskService;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use App\Repository\TaskRepository;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


set_exception_handler([new ErrorHandler(), 'handleException']);

class TaskServiceTest extends KernelTestCase
{

    private TaskService $taskService;

    private EntityManager $entityManager;

    protected function setUp(): void
    {

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $taskRepository = $kernel->getContainer()
            ->get(TaskRepository::class);

        $userService = $kernel->getContainer()
            ->get('App\Service\UserService');

        $this->taskService = new TaskService($taskRepository, $userService);

    }

    public function testGetAllTasks(): void
    {

        // Given

        // When
        $tasks = $this->taskService->getAllTasks();

        // Then
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);

    }

    public function testSaveTask(): void
    {

        // Given
        $task = (new Task())
            ->setCreatedAt(new DateTimeImmutable())
            ->setTitle('task title')
            ->setContent('task content')
            ->setIsDone(false);

        // When
        $savedTask = $this->taskService->saveTask($task);

        // Then
        $this->assertInstanceOf(Task::class, $savedTask);

    }

    public function testDeleteTask(): void
    {

        // Given
        $task = $this->entityManager->getRepository(Task::class)->findOneByTitle('task title');
        $id = $task->getId();

        // When
        $this->taskService->deleteTask($task);

        // Then
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $taskDeleted = $this->entityManager->getRepository(Task::class)->findOneById($id);
        $this->assertNotContains($taskDeleted, $tasks, "This task isn't known");

    }

    protected function tearDown(): void
    {

        $this->entityManager->close();

    }

}

// <?php

// namespace App\Tests\Service;

// use App\Entity\Task;
// use DateTimeImmutable;
// use App\Service\TaskService;
// use App\Service\UserService;
// use Doctrine\ORM\EntityManager;
// use App\Repository\TaskRepository;
// use Symfony\Component\ErrorHandler\ErrorHandler;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


// set_exception_handler([new ErrorHandler(), 'handleException']);

// class TaskServiceTest extends WebTestCase
// {

//     private KernelBrowser $client;
    
//     private TaskService $taskService;

//     private EntityManager $entityManager;

//     private TaskRepository $taskRepository;

//     protected function setUp(): void
//     {
//         $this->client = static::createClient();

//         $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

//         $this->taskService = $this->client->getContainer()->get(TaskService::class);

//         $this->taskRepository = $this->client->getContainer()->get(TaskRepository::class);
//     }

//     public function testGetAllTasks(): void
//     {

//         // Given

//         // When
//         $tasks = $this->taskService->getAllTasks();

//         // Then
//         $this->assertContainsOnlyInstancesOf(Task::class, $tasks);

//     }

//     public function testSaveTask(): void
//     {

//         // Given
//         $task = (new Task())
//             ->setCreatedAt(new DateTimeImmutable())
//             ->setTitle('task title')
//             ->setContent('task content')
//             ->setIsDone(false);

//         // When
//         $savedTask = $this->taskService->saveTask($task);

//         // Then
//         $this->assertInstanceOf(Task::class, $savedTask);

//     }

//     // public function testDeleteTask(): void
//     // {

//     //     // Given
//     //     $task = $this->taskRepository->findOneByTitle('task title');
//     //     $id = $task->getId();

//     //     // When
//     //     $this->taskService->deleteTask($task);

//     //     // Then
//     //     $tasks = $this->taskRepository->findAll();
//     //     $taskDeleted = $this->taskRepository->findOneById($id);
//     //     $this->assertNotContains($taskDeleted, $tasks, "This task isn't known");

//     // }

//     protected function tearDown(): void
//     {

//         $this->entityManager->close();

//     }

// }
