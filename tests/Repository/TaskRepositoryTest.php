<?php

namespace App\Tests\Repository;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{

    private EntityManager $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')
            ->getManager();
    }

    public function testSaveTask(): void
    {
        // Given
        $task = (new Task())
            ->setCreatedAt(new DateTimeImmutable())
            ->setTitle('task 2 title')
            ->setContent('task content')
            ->setIsDone(false);

        // When
        $this->entityManager->getRepository(Task::class)->saveTask($task);

        // Then
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $taskRegistered = $this->entityManager->getRepository(Task::class)->findOneByTitle('task 2 title');
        $this->assertContains($taskRegistered, $tasks, "This task isn't known");
    }

    public function testDeleteTask(): void
    {
        // Given
        $task = $this->entityManager->getRepository(Task::class)->findOneByTitle('task 2 title');
        $id = $task->getId();

        // When
        $this->entityManager->getRepository(Task::class)->deleteTask($task);

        // Then
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $taskDeleted = $this->entityManager->getRepository(Task::class)->findOneById($id);
        $this->assertNotContains($taskDeleted, $tasks, "This task isn't known");
    }

    public function testFindAll(): void
    {
        // Given

        // When
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        // Then
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

}
