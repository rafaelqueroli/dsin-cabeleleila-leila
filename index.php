<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Usuario;
use App\Controllers\ControllerUsuario;

// --- Roteamento simples via ?action= ---
$action = $_GET['action'] ?? null;

if ($action !== null) {
    $controller = new ControllerUsuario();

    match ($action) {
        'create' => $controller->create(),
        'update' => $controller->update(),
        'delete' => $controller->delete(),
        default  => header('location: index.php'),
    };
    exit;
}

// --- Página principal ---
$usuarios = Usuario::getUsuarios();

include __DIR__ . '/app/Views/includes/header.php';

switch(@$_REQUEST['page']) {
    case 'users':
        include __DIR__ . '/app/Views/includes/users.php';
        break;
    default:
        print "<h1>Bem Vindos!</h1>";
}

include __DIR__ . '/app/Views/includes/footer.php';
