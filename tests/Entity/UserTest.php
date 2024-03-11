<?php

namespace App\Tests\Entity;

use App\Entity\User;
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
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail('user@ex.com');

        // When
        $id = $user->getId();

        // Then
        $this->assertIsInt($id);
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

}
