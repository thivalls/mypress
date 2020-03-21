<?php
namespace src\Core;

use stdClass;
use src\Core\DB;
use src\Core\Message;

abstract class Model
{
    /** @var object|null $data */
    protected $data;

    /** @var \PDOException $fail */
    protected $fail;

    /** @var Message|null $message */
    protected $message;

    public function __construct()
    {
        $this->message = new Message;
    }

    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new stdClass;
        }
        $this->data->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /** @return object|null $data */
    protected function data(): ?object
    {
        return $this->data;
    }

    /** @return \PDOException $fail */
    protected function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /** @return Message|null $message */
    protected function message(): ?Message
    {
        return $this->message;
    }

    protected function create(string $entity, array $data): ?int
    {
        try {
            $columns = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            $stmt = DB::getInstance()->prepare("INSERT INTO {$entity} ({$columns}) VALUES ({$values});");
            $stmt->execute($this->filter($data));
            return DB::getInstance()->lastInsertId();
        } catch (\PDOException $e) {
            $this->fail = $e;
            return null;
        }
    }

    protected function read(string $select, string $params = null): ?\PDOStatement
    {
        try {
            $stmt = DB::getInstance()->prepare($select);
            parse_str($params, $params);
            foreach ($params as $key => $value) {
                $type = (is_numeric($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                $stmt->bindValue(":{$key}", $value, $type);
            }
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $e) {
            $this->fail = $e;
            return null;
        }
    }

    protected function update(string $entity, array $data, string $terms, string $params = null): ?int
    {
        try {
            $set = [];
            foreach (array_keys($data) as $column) {
                $set[] = "{$column} = :{$column}";
            }
            $set = implode(", ", $set);
            $stmt = DB::getInstance()->prepare("UPDATE {$entity} SET {$set} WHERE {$terms}");
            parse_str($params, $params);
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $e) {
            $this->fail = $e;
            return null;
        }
    }

    protected function delete(string $entity, string $terms, string $params): ?int
    {
        try {
            $stmt = DB::getInstance()->prepare("DELETE FROM {$entity} WHERE {$terms}");
            parse_str($params, $params);
            $stmt->execute($this->filter($params));
            return ($stmt->rowCount() ?? 1);
        } catch (\PDOException $e) {
            $this->fail = $e;
            return null;
        }
    }

    protected function safe(): ?array
    {
        $safe = (array) $this->data;
        foreach (static::$safe as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));
        }
        return $filter;
    }

    protected function required(): bool {
        $data = (array)$this->data();
        foreach(static::$required as $field) {
            if(empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}
