<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    /**
     * Summary of function __construct
     *
     * @param UserRepository $userRepository UserRepository
     * @param UserPasswordHasherInterface $userPasswordHasher UserPasswordHasherInterface
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    
    }

    /**
     * Summary of getAllUsers
     *
     * @return array<User>
     */
    public function getAllUsers(): array
    {

        return $this->userRepository->findAll();

    }

    /**
     * Summary of register
     *
     * @param User $user User
     * @param string $password password
     *
     * @return User
     */
    public function register(User $user, string $password): User
    {

        $user = (new User())
            ->setUsername($user->getUsername())
            ->setEmail($user->getEmail());

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        return $this->userRepository->saveUser($user);

    }

    /**
     * Summary of editUser
     *
     * @param User $user User
     *
     * @return User
     */
    public function editUser(User $user): User
    {

        return $this->userRepository->saveUser($user);

    }

    /**
     * Summary of deleteUser
     *
     * @param User $user User
     *
     * @return void
     */
    public function deleteUser(User $user): void
    {

        $this->userRepository->deleteUser($user);

    }

    public function dealRole(array $role): string
    {
        $length = count($role);
        for ($i = 0; $i <$length; $i++) {
            if ($role[$i] === "ROLE_ADMIN") {
                return 'ROLE_ADMIN';
            }
        }

        return 'ROLE_USER';

    }
}
