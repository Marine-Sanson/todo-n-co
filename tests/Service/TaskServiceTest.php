<?php

namespace App\Tests\Service;

use App\Entity\Task;
use DateTimeImmutable;
use App\Service\TaskService;
use Doctrine\ORM\EntityManager;
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
        
        $this->taskService = new TaskService($this->entityManager->getRepository(Task::class));

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
