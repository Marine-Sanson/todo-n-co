<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    private EntityManager $entityManager;

    protected function setUp(): void
    {

        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')
            ->getManager();

    }

    public function testGetId(): void
    {

        // Given
        $expectedId = 9999999999;
        $user = (new User())
        ->setId($expectedId);

        // When
        $id = $user->getId();

        // Then
        $this->assertEquals($expectedId, $id);

    }

    public function testGetUsername(): void
    {

        // Given
        $expectedUsername = "Username";
        $user = (new User())
        ->setUsername($expectedUsername);

        // When
        $username = $user->getUsername();

        // Then
        $this->assertEquals($expectedUsername, $username);

    }

    public function testGetEmail(): void
    {

        // Given
        $expectedEmail = "email@exemple.com";
        $user = (new User())
        ->setEmail($expectedEmail);

        // When
        $email = $user->getEmail();

        // Then
        $this->assertEquals($expectedEmail, $email);

    }

    public function testGetUserIdentifier(): void
    {

        // Given
        $expectedEmail = "email@exemple.com";
        $user = (new User())
            ->setEmail($expectedEmail);

        // When
        $userIdentifier = $user->getUserIdentifier();

        // Then
        $this->assertEquals($expectedEmail, $userIdentifier);

    }

    public function testGetRoles(): void
    {

        // Given
        $expectedRoles = ['ROLE_USER'];
        $user = (new User())
            ->setRoles($expectedRoles);

        // When
        $roles = $user->getRoles();

        // Then
        $this->assertEquals($expectedRoles, $roles);

    }

    public function testGetPassword(): void
    {

        // Given
        $expectedPassword = "password";
        $user = (new User())
            ->setPassword($expectedPassword);

        // When
        $password = $user->getPassword();

        // Then
        $this->assertEquals($expectedPassword, $password);

    }

    public function testGetTasks(): void
    {

        // Given
        $testuser = $this->entityManager->getRepository(User::class)->findOneByUsername('testuser');

        // When
        $tasks = $testuser->getTasks();

        // Then
        $this->assertContainsOnlyInstancesOf(Task::class, $tasks);

    }

    public function testAddTask(): void
    {

        // Given
        $testuser = $this->entityManager->getRepository(User::class)->findOneByUsername('testuser');

        $testdate = new DateTimeImmutable;
        $countTesttasksBefore = (count($testuser->getTasks()) + 1);

        $newtask = (new Task())
        ->setTitle('newtask')
        ->setContent('newtask content')
        ->setCreatedAt($testdate)
        ->setIsDone(0);

        // When
        $testuser->addTask($newtask);

        // Then
        $testtasksAfter = $testuser->getTasks();
        $this->assertCount($countTesttasksBefore, $testtasksAfter);

    }

    public function testRemoveTask(): void
    {

        // Given
        $testuser = $this->entityManager->getRepository(User::class)->findOneByUsername('testuser');

        $testdate = new DateTimeImmutable;

        $newtask = (new Task())
            ->setTitle('newtask')
            ->setContent('newtask content')
            ->setCreatedAt($testdate)
            ->setIsDone(0);
        $testuser->addTask($newtask);

        $countTesttasksBefore = (count($testuser->getTasks()) - 1);

        // When
        $testuser->removeTask($newtask);

        // Then
        $testtasksAfter = $testuser->getTasks();
        $this->assertCount($countTesttasksBefore, $testtasksAfter);

    }


}
