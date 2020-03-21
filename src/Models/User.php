<?php

namespace src\Models;

use src\Core\Model;

class User extends Model
{
    /** @var array $safe */
    protected static $safe = ["id", "created_at", "updated_at"];

    /** @var string $entity */
    protected static $entity = "users";

    /** @var array $required */
    protected static $required = ["first_name", "last_name", "email", "password"];

    public function init(string $first_name, string $last_name, string $email, string $password, string $document = null)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->document = $document;
        return $this;
    }

    public function find(string $terms, string $params, string $columns = "*"): ?User {
        $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE {$terms}", $params);
        if ($this->fail() || !$find->rowCount()) {
            return null;
        }
        return $find->fetchObject(__CLASS__);
    }

    public function findById(int $id, string $columns = "*"): ?User
    {
        return $this->find("id = :id", "id={$id}", $columns);
    }

    public function findByEmail(string $email, string $columns = "*"): ?User
    {
        return $this->find("email = :email", "email={$email}", $columns);
    }

    public function all(int $limit = 30, int $offset = 0, string $columns = "*")
    {
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");
        if ($this->fail() || !$all->rowCount()) {
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save(): ?User
    {
        if (!$this->required()) {
            $this->message->warning("Nome, sobrenome, email e senha são obrigatórios");
            return null;
        }

        if (!empty($this->id)) {
            $userId = $this->id;

            if ($this->find("email = :email AND id != :id", "email={$this->email}&id={$userId}")) {
                $this->message->warning("Usuário já existe");
                return null;
            }

            $this->update(self::$entity, $this->safe(), 'id = :id', "id={$userId}");
            if ($this->fail()) {
                $this->message->error("Houve um erro durante a atualização, verifique os dados");
                return null;
            }
        }

        if (empty($this->id)) {
            if ($this->findByEmail($this->email)) {
                $this->message->warning("Usuário já existe");
                return null;
            }

            $userId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message->error("Houve um erro durante o cadastro, verifique os dados");
                return null;
            }
        }

        $this->data = ($this->findById($userId))->data();
        return $this;
    }

    public function destroy(): ?User
    {
        if (!empty($this->id)) {  
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message->error("Houve um erro ao deletar, verifique os dados");
            return null;
        }

        $this->message->success("Usuário removido com sucesso.");
        $this->data = null;

        return $this;
    }
}
