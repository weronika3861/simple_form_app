<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function register(
        User $user,
        UserPasswordHasherInterface $passwordHasher
    ): void {
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->userRepository->save($user);
    }
}