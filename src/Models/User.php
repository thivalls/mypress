<?php

namespace src\Models;

use src\Core\Model;

class User extends Model
{
    /** @var array $safe */
    protected static $safe = ["id", "created_at", "updated_at"];

    /** @var string $entity */
    protected static $entity = "users";

    public function init(string $first_name, string $last_name, string $email, string $document = null)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->document = $document;
        return $this;
    }

    public function load(int $id, string $columns = "*"): ?User
    {
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE id = :id", "id={$id}");
        if ($this->fail() || !$load->rowCount()) {
            $this->message = "Usuário não encontrado";
            return null;
        }
        return $load->fetchObject(__CLASS__);
    }

    public function find(string $email, string $columns = "*"): ?User
    {
        $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE email = :email", "email={$email}");
        if ($this->fail() || !$find->rowCount()) {
            $this->message = "Usuário não encontrado";
            return null;
        }
        return $find->fetchObject(__CLASS__);
    }

    public function all(int $limit = 30, int $offset = 0, string $columns = "*")
    {
        $all = $this->read("SELECT {$columns} FROM " . self::$entity . " LIMIT :limit OFFSET :offset", "limit={$limit}&offset={$offset}");
        if ($this->fail() || !$all->rowCount()) {
            $this->message = "Nenhum usuário encontrado";
            return null;
        }
        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save(): ?User
    {
        if (!$this->validations()) {
            return null;
        }

        if (!empty($this->id)) {
            $userId = $this->id;

            $email = $this->read("SELECT id FROM users WHERE email = :email AND id != :id", "email={$this->email}&id={$userId}");
            if ($email->rowCount()) {
                $this->message = "Usuário já existe.";
                return null;
            }

            $this->update(self::$entity, $this->safe(), 'id = :id', "id={$userId}");
            if ($this->fail()) {
                $this->message = "Houve um erro durante a atualização, verifique os dados";
            }
            $this->message = "Usuário atualizado com sucesso";
        }

        if (empty($this->id)) {
            if ($this->find($this->email)) {
                $this->message = "Usuário já existe.";
                return null;
            }

            $userId = $this->create(self::$entity, $this->safe());
            if ($this->fail()) {
                $this->message = "Houve um erro durante o cadastro, verifique os dados";
            }
            $this->message = "Usuário cadastro com sucesso";
        }

        $this->data = $this->read("SELECT * FROM " . self::$entity . " WHERE id=:id", "id={$userId}")->fetch();
        return $this;
    }

    public function destroy(): ?User
    {
        if (!empty($this->id)) {  
            $this->delete(self::$entity, "id = :id", "id={$this->id}");
        }

        if ($this->fail()) {
            $this->message = "Houve um erro ao deletar, verifique os dados";
            return null;
        }

        $this->message = "Usuário removido com sucesso.";
        $this->data = null;

        return $this;
    }

    private function validations(): bool
    {
        if (empty($this->first_name) || empty($this->last_name) || empty($this->email)) {
            $this->message = "Os campos nome, sobrenome e email são obrigatórios";
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "Digite um email válido";
            return false;
        }

        return true;
    }
}
