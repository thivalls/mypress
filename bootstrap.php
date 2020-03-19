<?php

use src\Models\User;

require(__DIR__ . "/src/Support/Config.php");
require(__DIR__ . "/src/autoload.php");

    // init application here
    $model = new User;
    
    $user = $model->load(44);

    if($user) {
        var_dump($user);
    }
    
    