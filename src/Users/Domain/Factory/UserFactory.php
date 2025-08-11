<?php

declare(strict_types=1);

namespace App\Users\Domain\Factory;

use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserRepositoryInterface;

class UserFactory
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(string $name, string $password): User
    {
        $user = new User(
            name: $name,
            password: $password,
        );

        $this->userRepository->add($user);

        return $user;
    }
}