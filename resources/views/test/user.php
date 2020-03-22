<?php $v->layout("test::base", ["title" => "Editando usuário {$user->first_name}"]); ?>

<?php $v->start("nav"); ?>
    <a href="./" title="Voltar">Voltar</a>
<?php $v->stop(); ?>

<form action="" method="post" enctype="multipart/form-data">
    <?= ($user->message ?? ""), ($message ?? ""), csrf_input(); ?>
    <input type="text" name="first_name" value="<?= ($user->first_name ?? ""); ?>" placeholder="Primeiro nome">
    <input type="text" name="last_name" value="<?= ($user->last_name ?? ""); ?>" placeholder="Sobrenome">
    <input type="email" name="email" value="<?= ($user->email ?? ""); ?>" placeholder="Email">
    <button>Atualizar usuário</button>
</form>