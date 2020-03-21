
<?php

use src\Models\User;

require_once(__DIR__ . '/bootstrap.php');

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

if($post) {
    $data = (object)$post;

    if(!csrf_verify($post)) {
        $message = message()->error("Erro em enviar, tento novamente");
    }else {
        $user = new User;
        $user->init(
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->password
        );
        $user->save();
        $message = message()->success("Usuário cadastrado com sucesso");
    }
    var_dump($data, (isset($user) ? $user : ''), $user->message );

}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
    .trigger {
        padding: 10px;
        border: 2px solid;
    }

    .trigger.error {
        color: red;
        border-color: red;
    }

    .trigger.success {
        color: green;
        border-color: green;
    }
</style>
</head>
<body>
<form name="post" action="#" method="post" enctype="multipart/form-data" autocomplete="off" novalidate>
    <?= ($user->message ?? ""), ($message ?? ""), csrf_input(); ?>
    <input type="text" name="first_name" value="<?= ($data->first_name ?? ""); ?>" placeholder="Primeiro nome">
    <input type="text" name="last_name" value="<?= ($data->last_name ?? ""); ?>" placeholder="Sobrenome">
    <input type="email" name="email" value="<?= ($data->email ?? ""); ?>" placeholder="Email">
    <input type="password" name="password" value="<?= ($data->password ?? ""); ?>" placeholder="Senha">
    <button>Cadastre-se</button>
</form>
</body>
</html>