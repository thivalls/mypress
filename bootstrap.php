<?php
use src\Connection\DB;
use src\Models\Model;
use src\Models\User;

require_once(__DIR__ . "/src/autoload.php");

    $model = new User;

    $user = $model->init(
        "Junior", 
        "Andrade", 
        "cursoddees22@upinside.com", 
        54545454
    );
    
    $user->save();

    var_dump($user);
    