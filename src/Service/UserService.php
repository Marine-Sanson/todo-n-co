<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}
    
    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

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

    public function editUser(User $user): User
    {
        return $this->userRepository->saveUser($user);
    }
}
