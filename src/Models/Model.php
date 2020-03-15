<?php
    declare(strict_types=1);

    namespace src\Models;

    use src\Connection\DB;
use stdClass;

abstract class Model {
        /** @var object|null $data */
        protected $data;

        /** @var \PDOException $fail */
        protected $fail;

        /** @var string|null $message */
        protected $message;
        
        public function __set($name, $value)
        {
            if(empty($this->data)) {
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
        protected function data(): ?object {
            return $this->data;
        }

        /** @return \PDOException $fail */
        protected function fail(): ?\PDOException {
            return $this->fail;
        }

        /** @return string|null $fail */
        protected function message(): ?string {
            return $this->message;
        }

        protected function read(string $select, string $params = null): ?\PDOStatement {
            try {
                $stmt = DB::getInstance()->prepare($select);
                parse_str($params, $params);
                foreach($params as $key => $value) {
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

        protected function create() {

        }

        protected function update() {

        }

        protected function delete() {

        }

        protected function safe() {

        }

        protected function filter() {

        }
    }