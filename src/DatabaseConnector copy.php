<?php

/*
 * This file is part of the NextPHP package.
 *
 * (c) Vedat Yıldırım <vedat@nextphp.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace NextPHP\Data;

class DatabaseConnector2
{
    private $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new \PDO(
            $config['dsn'],
            $config['username'],
            $config['password']
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }

    public function query($query)
    {
        return $this->pdo->query($query);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function exec($query)
    {
        return $this->pdo->exec($query);
    }

    public function createTable(string $tableName, array $columns, array $foreignKeys = [])
    {
        $columnsSql = [];
        foreach ($columns as $column => $definition) {
            $columnsSql[] = "$column $definition";
        }
        $columnsSql = implode(', ', $columnsSql);

        $query = "CREATE TABLE IF NOT EXISTS $tableName ($columnsSql";

        if (!empty($foreignKeys)) {
            $foreignKeysSql = [];
            foreach ($foreignKeys as $foreignKey) {
                $foreignKeysSql[] = "FOREIGN KEY ({$foreignKey['column']}) REFERENCES {$foreignKey['references']}({$foreignKey['referencedColumn']}) ON DELETE {$foreignKey['onDelete']}";
            }
            $foreignKeysSql = implode(', ', $foreignKeysSql);
            $query .= ", $foreignKeysSql";
        }

        $query .= ")";

        $this->pdo->exec($query);
    }

    public function dropTable(string $tableName)
    {
        $query = "DROP TABLE IF EXISTS $tableName";
        $this->pdo->exec($query);
    }

    public function updateTable(string $tableName, array $columns)
    {
        foreach ($columns as $column => $definition) {
            $query = "ALTER TABLE $tableName ADD COLUMN $column $definition";
            $this->pdo->exec($query);
        }
    }
}