<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;

    public function findByEmail(string $email): ?User;

    public function existsByEmail(string $email): bool;

    public function create(User $user): User;

    public function update(User $user): bool;

    public function delete(string $id): bool;

    public function updateLastLogin(
        string $userId,
        string $ipAddress
    ): bool;

    public function saveRememberToken(
        string $userId,
        ?string $token
    ): bool;
}