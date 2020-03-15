<?php

use src\Connection\DB;
use src\Models\Model;
use src\Models\User;

require_once(__DIR__ . "/src/autoload.php");

    $user = new User;

    var_dump($user->all(5, 5, "id, first_name, email"));
    