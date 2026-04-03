<?php

require __DIR__ . '/../vendor/autoload.php';

$action = $_GET['action'] ?? null;
$entity = $_GET['entity'] ?? null;

if ($action !== null) {
    match ($entity) {
        'usuario' => (function() use ($action) {
            $c = new \App\Controllers\ControllerUsuario();
            match ($action) {
                'create' => $c->create(),
                'update' => $c->update(),
                'delete' => $c->delete(),
                default  => header('Location: /'),
            };
        })(),

        'servico' => (function() use ($action) {
            $c = new \App\Controllers\ControllerServico();
            match ($action) {
                'create' => $c->create(),
                'update' => $c->update(),
                'delete' => $c->delete(),
                default  => header('Location: /'),
            };
        })(),

        // 'agendamento' => (function() use ($action) {
        //     $c = new \App\Controllers\ControllerAgendamento();
        //     match ($action) {
        //         'create' => $c->create(),
        //         'update' => $c->update(),
        //         'delete' => $c->delete(),
        //         default  => header('Location: /'),
        //     };
        // })(),

        default => header('Location: /'),
    };
    exit;
}

// Página principal
$usuarios     = \App\Models\Usuario::getUsuarios();
$servicos     = \App\Models\Servico::getServicos();
// $agendamentos = \App\Models\Agendamento::getAgendamentos();

include __DIR__ . '/../app/Views/includes/header.php';

switch ($_GET['page'] ?? null) {
    case 'users':
        include __DIR__ . '/../app/Views/includes/users.php';
        break;
    case 'servicos':
        include __DIR__ . '/../app/Views/includes/servicos.php';
        break;
    case 'agendamentos':
        include __DIR__ . '/../app/Views/includes/agendamentos.php';
        break;
    default:
        print "<h1>Bem Vindos!</h1>";
}

include __DIR__ . '/../app/Views/includes/footer.php';