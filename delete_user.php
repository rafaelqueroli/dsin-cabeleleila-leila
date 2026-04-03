<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Entity\Usuario;

$objUsuario = Usuario::getUsuario($_GET['id']);

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
if (isset($_POST['delete'])) {

    $objUsuario->deleteUsuario();

    header('location: index.php?status=success');
    exit;
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/alert_delete.php';
include __DIR__ . '/includes/footer.php';
