<?php

    namespace src\Connection;

    use \PDO;
    use \PDOException;

    class DB {
        private const DBHOST = "localhost";
        private const DBPORT = 8889;
        private const DBUSER = "root";
        private const DBPASS = "root";
        private const DBNAME = "pdo";

        private const OPTIONS = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ];

        private static $instance;

        /** @return PDO */
        public static function getInstance(): PDO {
            if(empty(self::$instance)) {
                try {
                    self::$instance = new PDO("mysql:host=" . self::DBHOST . ";dbname=" . self::DBNAME . ";dbport=" . self::DBPORT, self::DBUSER, self::DBPASS, self::OPTIONS);    
                } catch (PDOException $e) {
                    return $e->getMessage();
                }
            }

            return self::$instance;            
        }

        final private function __construct(){}
        final private function __clone(){}
    }