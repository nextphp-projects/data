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

use ReflectionClass;

class BaseRepository2
{
    protected $db;
    private $entityClass;

    public function __construct()
    {
        $this->entityClass = $this->getEntityClass();
        $this->db = $this->initializeDatabase();
        $this->initializeTable();
    }

    private function getEntityClass(): string
    {
        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getAttributes(Repository::class);
        if (!empty($attributes)) {
            $repositoryAttribute = $attributes[0]->newInstance();
            return $repositoryAttribute->entityClass;
        }
        throw new \Exception("Repository attribute not found on class " . get_class($this));
    }

    private function initializeDatabase()
    {
        $config = [
            'dsn' => 'mysql:host=localhost;dbname=nextphp2',
            'username' => 'root',
            'password' => '',
        ];
        return new DatabaseConnector($config);
    }

    private function initializeTable()
    {
        $tableName = $this->getTableName();
        $columns = $this->getColumnDefinitions();

        $this->db->createTable($tableName, $columns);
    }

    private function getColumnDefinitions(): array
    {
        $reflection = new \ReflectionClass($this->entityClass);
        $properties = $reflection->getProperties();
        $columns = [];

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(\NextPHP\Data\Persistence\Column::class);
            if (!empty($attributes)) {
                $column = $attributes[0]->newInstance();
                $columns[$property->getName()] = $column->definition;
            }
        }

        return $columns;
    }

    public function save(array $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($data), '?');

        $query = 'INSERT INTO ' . $this->getTableName() . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = $this->db->prepare($query);
        $stmt->execute($values);

        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $set = [];
        foreach ($columns as $column) {
            $set[] = "$column = ?";
        }

        $query = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(', ', $set) . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute([...$values, $id]);
    }

    public function delete(int $id)
    {
        $query = 'DELETE FROM ' . $this->getTableName() . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
    }

    public function find(int $id)
    {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $query = 'SELECT * FROM ' . $this->getTableName();
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ';
        $params = [];
        foreach ($criteria as $field => $value) {
            $query .= $field . ' = ? AND ';
            $params[] = $value;
        }
        $query = rtrim($query, ' AND ');

        if ($orderBy) {
            $query .= ' ORDER BY ' . implode(', ', array_map(
                    fn($key, $value) => "$key $value",
                    array_keys($orderBy),
                    $orderBy
                ));
        }

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function count(array $criteria)
    {
        $query = 'SELECT COUNT(*) FROM ' . $this->getTableName() . ' WHERE ';
        $params = [];
        foreach ($criteria as $field => $value) {
            $query .= $field . ' = ? AND ';
            $params[] = $value;
        }
        $query = rtrim($query, ' AND ');
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function distinct($field, array $criteria = [])
    {
        $query = 'SELECT DISTINCT ' . $field . ' FROM ' . $this->getTableName();
        $params = [];
        if (!empty($criteria)) {
            $query .= ' WHERE ';
            foreach ($criteria as $field => $value) {
                $query .= $field . ' = ? AND ';
                $params[] = $value;
            }
            $query = rtrim($query, ' AND ');
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function orderBy(array $criteria, array $orderBy)
    {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ';
        $params = [];
        foreach ($criteria as $field => $value) {
            $query .= $field . ' = ? AND ';
            $params[] = $value;
        }
        $query = rtrim($query, ' AND ');

        if ($orderBy) {
            $query .= ' ORDER BY ' . implode(', ', array_map(
                    fn($key, $value) => "$key $value",
                    array_keys($orderBy),
                    $orderBy
                ));
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function having(array $criteria, array $having)
    {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ';
        $params = [];
        foreach ($criteria as $field => $value) {
            $query .= $field . ' = ? AND ';
            $params[] = $value;
        }
        $query = rtrim($query, ' AND ');

        if ($having) {
            $query .= ' HAVING ' . implode(' AND ', array_map(
                    fn($key, $value) => "$key $value",
                    array_keys($having),
                    $having
                ));
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTableName(): string
    {
        $reflection = new \ReflectionClass($this->entityClass);
        $attributes = $reflection->getAttributes(\NextPHP\Data\Persistence\Entity::class);

        if (empty($attributes)) {
            throw new \Exception("Entity attribute not found for class " . $this->entityClass);
        }

        $entityAttribute = $attributes[0]->newInstance();
        if (!isset($entityAttribute->table)) {
            throw new \Exception("Table name not found in Entity attribute for class " . $this->entityClass);
        }

        return $entityAttribute->table;
    }
}