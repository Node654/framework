<?php

namespace Nodest\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Nodest\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';

    public function __construct(
        private Connection $connection,
        private string $migrationsPath
    ) {}

    public function execute(array $arguments = []): int
    {
        try {
            $this->createMigrationsTable();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getMigrationFiles();

            $migrationsToPerform = array_values(array_diff($migrationFiles, $appliedMigrations));

            $schema = new Schema;

            foreach ($migrationsToPerform as $migration) {
                $migrationInstance = require $this->migrationsPath."/$migration";

                $migrationInstance->up($schema);

                $this->addMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }
            echo 'Все миграции выполнены!'.PHP_EOL;
        } catch (\Throwable $e) {
            throw $e;
        }

        return 0;
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (! $schemaManager->tableExists('migrations')) {
            $schema = new Schema;

            $table = $schema->createTable('migrations');
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING, ['length' => 255]);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);

            $table->setPrimaryKey(['id']);

            $sql = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sql[0]);

            echo 'Table migrations created!'.PHP_EOL;
        }
    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from('migrations')
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        $filteredFiles = array_diff($migrationFiles, ['.', '..']);

        return array_values($filteredFiles);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('migrations')->values(['migration' => ':migration'])->setParameter('migration', $migration)->executeQuery();
    }
}
