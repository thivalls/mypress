<?php


require_once(__DIR__ . '/vendor/autoload.php');

use src\Support\Thumb;
use src\Support\Upload;

// $upload = new Upload();

// $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
// if($post && $_POST['send']) {
//     $u = $upload->image($_FILES['file'], $post['name'] ?? $_FILES['name']);

//     if(!empty($u)) {
//         var_dump($u);
//     }else {
//         echo $upload->message();
//     }
// }

// $formSend = 'image';

$t = new Thumb;
var_dump($t);
