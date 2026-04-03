<?php

require __DIR__ . '/vendor/autoload.php';

define('TITLE', 'Editar Usuário');

use \App\Entity\Usuario;

// Validação de ID
if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
    header('location: index.php?status=error');
    exit;
}

// Consulta à Vaga
$objUsuario = Usuario::getUsuario($_GET['id']);


// Validação do Usuário
if (!$objUsuario instanceof Usuario) {
    header('location: index.php?status=error');
    exit;
}

// Validação do Post
if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
    $objUsuario->name    = $_POST['name'];
    $objUsuario->surname = $_POST['surname'];
    $objUsuario->email   = $_POST['email'];
    $objUsuario->phone_n = $_POST['phone_n'];
    $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $objUsuario->role    = $_POST['role'];

    $objUsuario->updateUsuario();

    header('location: index.php?status=success');
    exit;
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/form_user.php';
include __DIR__ . '/includes/footer.php';
