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

    /**
     * Summary of userService
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * Summary of entityManager
     *
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * Summary of userPasswordHasher
     *
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;


    /**
     * Function setUp
     */
    protected function setUp(): void
    {

        self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')
            ->getManager();

        $this->userPasswordHasher = static::$kernel->getContainer()->get('security.user_password_hasher');

        $this->userService = static::$kernel->getContainer()
            ->get('App\Service\UserService');

    }


    /**
     * Function testGetAllUsers
     */
    public function testGetAllUsers(): void
    {

        // Given

        // When
        $users = $this->userService->getAllUsers();

        // Then
        $this->assertContainsOnlyInstancesOf(User::class, $users);

    }


    /**
     * Function testRegister
     */
    public function testRegister(): void
    {

        // Given
        $user = (new User())
            ->setUsername('newtestuser')
            ->setEmail('newtestuser@ex.com')
            ->setRoles(['ROLE_ADMIN']);

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
        $userRegistered = $this->entityManager->getRepository(User::class)->findOneByEmail('newtestuser@ex.com');
        $this->assertContains($userRegistered, $users, "This user isn't known");

    }


    /**
     * Function testEditUser
     */
    public function testEditUser(): void
    {

        // Given
        $userRegistered = $this->entityManager->getRepository(User::class)->findOneByEmail('newtestuser@ex.com');
        $newrole = ['ROLE_USER'];
        $userRegistered->setRoles($newrole);

        // When
        $this->userService->editUser($userRegistered);

        // Then
        $userEdited = $this->entityManager->getRepository(User::class)->findOneByEmail('newtestuser@ex.com');
        $this->assertEquals($newrole, $userEdited->getRoles(), "This user wasn't changed");

    }


    /**
     * Function testDeleteUser
     */
    public function testDeleteUser(): void
    {

        // Given
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail('newtestuser@ex.com');

        // When
        $this->userService->deleteUser($user);

        // Then
        $userDeleted = $this->entityManager->getRepository(User::class)->findOneByEmail('newtestuser@ex.com');
        $this->assertEmpty($userDeleted);

    }


    /**
     * Function tearDown
     */
    protected function tearDown(): void
    {

        $this->entityManager->close();

    }


}
