<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Database $database
    ) {
    }

    public function findById(string $id): ?User
    {
        $sql = <<<SQL
SELECT *
FROM users
WHERE id = :id
AND deleted_at IS NULL
LIMIT 1
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User($row);
    }

    public function findByEmail(string $email): ?User
    {
        $sql = <<<SQL
SELECT *
FROM users
WHERE email = :email
AND deleted_at IS NULL
LIMIT 1
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        $statement->execute([
            'email' => strtolower($email)
        ]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User($row);
    }

    public function existsByEmail(string $email): bool
    {
        $sql = <<<SQL
SELECT EXISTS(
    SELECT 1
    FROM users
    WHERE email = :email
    AND deleted_at IS NULL
)
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        $statement->execute([
            'email' => strtolower($email)
        ]);

        return (bool) $statement->fetchColumn();
    }

    public function create(User $user): User
    {
        $sql = <<<SQL
INSERT INTO users
(
    name,
    email,
    password,
    avatar,
    timezone,
    locale,
    theme,
    is_active
)
VALUES
(
    :name,
    :email,
    :password,
    :avatar,
    :timezone,
    :locale,
    :theme,
    :is_active
)
RETURNING *
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        $statement->execute([
            'name'       => $user->name,
            'email'      => strtolower($user->email),
            'password'   => $user->password,
            'avatar'     => $user->avatar,
            'timezone'   => $user->timezone,
            'locale'     => $user->locale,
            'theme'      => $user->theme,
            'is_active'  => $user->is_active
        ]);

        return new User(
            $statement->fetch(PDO::FETCH_ASSOC)
        );
    }

    public function update(User $user): bool
    {
        $sql = <<<SQL
UPDATE users
SET
    name = :name,
    avatar = :avatar,
    timezone = :timezone,
    locale = :locale,
    theme = :theme,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
AND deleted_at IS NULL
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        return $statement->execute([
            'id'        => $user->id,
            'name'      => $user->name,
            'avatar'    => $user->avatar,
            'timezone'  => $user->timezone,
            'locale'    => $user->locale,
            'theme'     => $user->theme
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = <<<SQL
UPDATE users
SET
    deleted_at = CURRENT_TIMESTAMP,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
AND deleted_at IS NULL
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }

    public function updateLastLogin(
        string $userId,
        string $ipAddress
    ): bool {

        $sql = <<<SQL
UPDATE users
SET
    last_login_at = CURRENT_TIMESTAMP,
    last_login_ip = :ip,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        return $statement->execute([
            'id' => $userId,
            'ip' => $ipAddress
        ]);
    }

    public function saveRememberToken(
        string $userId,
        ?string $token
    ): bool {

        $sql = <<<SQL
UPDATE users
SET
    remember_token = :token,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
SQL;

        $statement = $this->database
            ->getConnection()
            ->prepare($sql);

        return $statement->execute([
            'id' => $userId,
            'token' => $token
        ]);
    }
}