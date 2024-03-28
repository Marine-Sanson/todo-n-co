<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskTest extends KernelTestCase
{

    private EntityManager $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;


    /**
     * Function setUp
     */
    protected function setUp(): void
    {

        self::bootKernel();

        $this->entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userPasswordHasher = static::$kernel->getContainer()->get('security.user_password_hasher');

    }


    /**
     * Function testGetCreatedAt
     */
    public function testGetCreatedAt(): void
    {

        // Given - prépare les éléments
        $expectedCreatedAt = new DateTimeImmutable();
        $task = (new Task())
            ->setCreatedAt($expectedCreatedAt);

        // When - execute la commande testée
        $date = $task->getCreatedAt();

        // Then - je m'assure que c'est bon
        $this->assertEquals($expectedCreatedAt, $date);

    }


    /**
     * Function testGetTitle
     */
    public function testGetTitle(): void
    {

        // Given
        $expectedTitle = "Expected title";
        $task = (new Task())
            ->setTitle($expectedTitle);

        // When
        $title = $task->getTitle();

        // Then
        $this->assertEquals($expectedTitle, $title);

    }


    /**
     * Function testGetContent
     */
    public function testGetContent(): void
    {

        // Given
        $expectedContent = "Expected content";
        $task = (new Task())
            ->setContent($expectedContent);

        // When
        $content = $task->getContent();

        // Then
        $this->assertEquals($expectedContent, $content);

    }


    /**
     * Function testIsDone
     */
    public function testIsDone(): void
    {

        // Given
        $expectedIsDone = true;
        $task = (new Task())
            ->setIsDone($expectedIsDone);

        // When
        $isDone = $task->isDone();

        // Then
        $this->assertEquals($expectedIsDone, $isDone);

    }


    /**
     * Function testIsNotDone
     */
    public function testIsNotDone(): void
    {

        // Given
        $expectedIsNotDone = false;
        $task = (new Task())
            ->setIsDone($expectedIsNotDone);

        // When
        $isNotDone = $task->isDone();

        // Then
        $this->assertEquals($expectedIsNotDone, $isNotDone);

    }


    /**
     * Function testGetUser
     */
    public function testGetUser(): void
    {

        // Given
        $task = $this->entityManager->getRepository(Task::class)->findOneByTitle('testtask');

        // When
        $taskUser = $task->getUser();

        // Then
        $this->assertInstanceOf(User::class, $taskUser);

    }


    public function testSetUser(): void
    {

        // Given
        $user = (new User())
            ->setUsername('new username 2')
            ->setEmail('newemail2@ex.com');
        $password = 'password';

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        $task = (new Task())
            ->setCreatedAt(new DateTimeImmutable())
            ->setTitle('task 2 title')
            ->setContent('task content')
            ->setIsDone(false);

        // When
        $task->setUser($user);

        // Then
        $taskUser = $task->getUser();
        $this->assertInstanceOf(User::class, $taskUser);

    }


}
