<?php

use src\Models\User;
use src\Core\View;

require_once(__DIR__ . '/vendor/autoload.php');

/** @var View $plates */
$view = new View();
$view->path("test", "test");

if(empty($_GET['id'])) {
    echo $view->render("test::list", [
        "title" => "Lista de usuários", 
        "list" => (new User())->all(5)
        ]);
}else {
    echo $view->render("test::user", [
        "title" => "Listagem de usuários",
        "user" => (new User())->findById($_GET['id'])
        ]);
}

// $plates = League\Plates\Engine::create(__DIR__ . "/resources/views", "php");

// $plates->addFolder("test", "test");

// if(empty($_GET['id'])) {
//     $list = (new User)->all(5);
//     echo $plates->render("test::list", [
//         "title" => "Listagem de usuários",
//         "list" => $list
//     ]);
// }else {
//     $user = (new User)->findById($_GET['id']);
//     echo $plates->render("test::user", [
//         "title" => "Listagem de usuários",
//         "user" => $user
//         ]);
// }