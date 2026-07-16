<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;

    public function __construct(Config $config)
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $config->get('database.host'),
            $config->get('database.port'),
            $config->get('database.database')
        );

        $this->connection = new PDO(
            $dsn,
            $config->get('database.username'),
            $config->get('database.password'),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $statement = $this->connection->prepare($sql);

        $statement->execute($params);

        $result = $statement->fetch();

        return $result ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $statement = $this->connection->prepare($sql);

        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $statement = $this->connection->prepare($sql);

        return $statement->execute($params);
    }

    public function insert(string $sql, array $params = []): array
    {
        $statement = $this->connection->prepare($sql);

        $statement->execute($params);

        return $statement->fetch();
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollback(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }

    public function transaction(callable $callback): mixed
    {
        try {

            $this->beginTransaction();

            $result = $callback();

            $this->commit();

            return $result;

        } catch (\Throwable $e) {

            $this->rollback();

            throw $e;
        }
    }
}