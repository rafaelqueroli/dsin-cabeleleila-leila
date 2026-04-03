<?php

require __DIR__ . '/../vendor/autoload.php';

use \App\DB\Pagination;
use App\Models\Usuario;

$action = $_GET['action'] ?? null;
$entity = $_GET['entity'] ?? null;

if ($action !== null) {
    match ($entity) {
        'usuario' => (function () use ($action) {
            $c = new \App\Controllers\ControllerUsuario();
            match ($action) {
                'create' => $c->create(),
                'update' => $c->update(),
                'delete' => $c->delete(),
                default  => header('location: /'),
            };
        })(),

        'servico' => (function () use ($action) {
            $c = new \App\Controllers\ControllerServico();
            match ($action) {
                'create' => $c->create(),
                'update' => $c->update(),
                'delete' => $c->delete(),
                default  => header('location: /'),
            };
        })(),

        // 'agendamento' => (function () use ($action) {
        //     $c = new \App\Controllers\ControllerAgendamento();
        //     match ($action) {
        //         'create' => $c->create(),
        //         'update' => $c->update(),
        //         'delete' => $c->delete(),
        //         default  => header('location: /'),
        //     };
        // })(),

        default => header('location: /'),
    };
    exit;
}

// Busca
$search = $_GET['search'] ?? '';

// Filtro de Função
$role_search = $_GET['role_search'] ?? '';
$role_search = in_array($role_search, ['c','f','a']) ? $role_search : null;

// Condições SQL
$conditions = [
    strlen($search) ? 'name LIKE "%' . $search . '%" OR surname LIKE "%' . $search . '%"' : '',
    strlen($role_search) ? 'role = "' . $role_search . '"' : ''
];
//  Remove condições vazias
$conditions = array_filter($conditions);

// Clausula Where
$where = implode(' AND ', $conditions);

// Quantidade total de vags
$len_usuarios = Usuario::getLenUsuarios($where);

// Paginação
$objPagination = new Pagination($len_usuarios, $_GET['p'] ?? 1, 5);

// Página principal
$usuarios     = \App\Models\Usuario::getUsuarios($where,null,$objPagination->getLimit());
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
