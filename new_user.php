<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Entity\Usuario;

// Validação do Post
if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
    $objUsuario          = new Usuario;
    $objUsuario->name    = $_POST['name'];
    $objUsuario->surname = $_POST['surname'];
    $objUsuario->email   = $_POST['email'];
    $objUsuario->phone_n = $_POST['phone_n'];
    $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $objUsuario->role    = $_POST['role'];

    $objUsuario->registerUsuario();

    header('location: index.php?status=success');
    exit;
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/form_register_user.php';
include __DIR__ . '/includes/footer.php';
