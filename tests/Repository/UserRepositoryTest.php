<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends KernelTestCase
{

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

        $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();

        $this->userPasswordHasher = static::$kernel->getContainer()->get('security.user_password_hasher');

    }


    /**
     * Function testFindAll
     */
    public function testFindAll(): void
    {

        // Given

        // When
        $users = $this->entityManager->getRepository(User::class)->findAll();

        // Then
        $this->assertContainsOnlyInstancesOf(User::class, $users);

    }


    /**
     * Function testSaveUser
     */
    public function testSaveUser(): void
    {

        // Given
        $user = (new User())
            ->setUsername('new username 2')
            ->setEmail('newemail2@ex.com')
            ->setRoles(['ROLE_USER']);

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


    /**
     * Function testUpgradePassword
     */
    public function testUpgradePassword(): void
    {

        // Given
        $testuser = $this->entityManager->getRepository(User::class)->findOneByUsername('testuser');
        $newpass = 'newpass';

        // When
        $this->entityManager->getRepository(User::class)->upgradePassword($testuser, $newpass);

        //then
        $this->assertEquals($newpass, $testuser->getPassword());

    }


    /**
     * Function testDeleteUser
     */
    public function testDeleteUser(): void
    {

        // Given
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail('newemail2@ex.com');

        // When
        $this->entityManager->getRepository(User::class)->deleteUser($user);

        // Then
        $userDeleted = $this->entityManager->getRepository(User::class)->findOneByEmail('newemail2@ex.com');
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
