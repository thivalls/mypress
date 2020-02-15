<?php

    use src\Connection\DB;

    require_once(__DIR__ . "/src/autoload.php");

    /** 
     * Get connection instance
     * @var src\Connection\DB $conn 
     */
    $users = DB::getInstance()->query("select * from users")->fetchAll();

    var_dump($users);
