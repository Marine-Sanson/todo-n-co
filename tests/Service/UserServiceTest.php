<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

set_exception_handler([new ErrorHandler(), 'handleException']);

class UserServiceTest extends WebTestCase
{

    private UserService $userService;

    private EntityManager $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')
            ->getManager();

        $this->userPasswordHasher = static::$kernel->getContainer()->get('security.user_password_hasher');

        $this->userService = new UserService($this->entityManager->getRepository(User::class), $this->userPasswordHasher);
    }

    public function testGetAllUsers(): void
    {
        // Given

        // When
        $users = $this->userService->getAllUsers();

        // Then
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    public function testRegister(): void
    {
        // Given
        $user = (new User())
            ->setUsername('new username')
            ->setEmail('newemail@ex.com');
        $password = 'password';

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        // When
        $this->userService->register($user, $password);

        // Then
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $userRegistered = $this->entityManager->getRepository(User::class)->findOneByEmail('newemail@ex.com');
        $this->assertContains($userRegistered, $users, "This user isn't known");
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

}
