<?php

namespace App\Tests\Service;

use App\Entity\Task;
use DateTimeImmutable;
use App\Service\TaskService;
use Doctrine\ORM\EntityManager;
use App\Repository\TaskRepository;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


set_exception_handler([new ErrorHandler(), 'handleException']);

class TaskServiceTest extends KernelTestCase
{

    /**
     * Summary of taskService
     *
     * @var TaskService
     */
    private TaskService $taskService;

    /**
     * Summary of entityManager
     *
     * @var EntityManager
     */
    private EntityManager $entityManager;


    /**
     * Function setUp
     */
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


    /**
     * Function testGetAllTasks
     */
    public function testGetAllTasks(): void
    {

        // Given

        // When
        $tasks = $this->taskService->getAllTasks();

        // Then
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);

    }


    /**
     * Function testSaveTask
     */
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


    /**
     * Function testDeleteTask
     */
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


    /**
     * Function tearDown
     */
    protected function tearDown(): void
    {

        $this->entityManager->close();

    }


}
