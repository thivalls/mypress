<?php


require_once(__DIR__ . '/vendor/autoload.php');

use src\Support\Upload;

$upload = new Upload();

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
if($post && $_POST['send'] == "image") {
    $u = $upload->image($_FILES['file'], $post['name'] ?? $_FILES['name']);
    // // $u = $upload->image($_FILES['file'], $post['name']);

    if(!empty($u)) {
        var_dump($u);
    }else {
        echo $upload->message();
    }
}

$formSend = 'image';

?>
<form action="./" name="upload" method="post" enctype="multipart/form-data">
    <input name="send" value="<?= ($formSend ?? ""); ?>"/>
    <input name="name" type="text" value="Nome do arquivo"/>
    <input name="file" type="file" required/>
    <button class="green"><?= $formSend; ?></button>
</form>
