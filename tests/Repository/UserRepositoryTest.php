<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserRepositoryTest extends KernelTestCase
{

    private EntityManager $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')
            ->getManager();

        $this->userPasswordHasher = static::$kernel->getContainer()->get('security.user_password_hasher');
    }

    public function testFindAll(): void
    {
        // Given

        // When
        $users = $this->entityManager->getRepository(User::class)->findAll();

        // Then
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    public function testSaveUser(): void
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

        // When
        $this->entityManager->getRepository(User::class)->saveUser($user);

        // Then
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $userRegistered = $this->entityManager->getRepository(User::class)->findOneByEmail('newemail2@ex.com');
        $this->assertContains($userRegistered, $users, "This user isn't known");
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

}
