<?php

require __DIR__ . '/../vendor/autoload.php';

use App\DB\Pagination;
use App\Models\Usuario;
use App\Models\Servico;
use App\Session\Login;

// Obriga o usuário a estar loggado
// Obriga o usuário a estar logado (exceto na tela de login)
$page = $_GET['page'] ?? null;

if ($page !== 'login') {
    Login::requrireLogin();
} else {
    include __DIR__ . '/../app/Views/includes/login.php';
    exit;
}

if ($page === 'logout') {
    Login::logoutFunction();
    exit;
}

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

switch ($page) {
    case 'users':
        // Filtros
        $search      = $_GET['search'] ?? '';
        $role_search = $_GET['role_search'] ?? '';
        $role_search = in_array($role_search, ['c', 'a']) ? $role_search : null;

        // Condições SQL
        $conditions = array_filter([
            strlen($search)      ? 'name LIKE "%' . $search . '%" OR surname LIKE "%' . $search . '%"' : '',
            strlen($role_search) ? 'role = "' . $role_search . '"' : '',
        ]);
        $where = implode(' AND ', $conditions);

        // Paginação e dados
        $len_usuarios   = Usuario::getLenUsuarios($where);
        $objPagination  = new Pagination($len_usuarios, $_GET['p'] ?? 1, 5);
        $usuarios       = Usuario::getUsuarios($where, null, $objPagination->getLimit());
        break;

    case 'servicos':
        // Filtros
        $search = $_GET['search'] ?? '';
        $cat_search     = $_GET['cat_search'] ?? '';
        $cat_search     = in_array($cat_search, ['c', 'u', 'e']) ? $cat_search : null;

        // Condições SQL
        $conditions = array_filter([
            strlen($search) ? 'name LIKE "%' . $search . '%"' : '',
            strlen($cat_search)     ? 'cat = "' . $cat_search . '"' : '',
        ]);
        $where = implode(' AND ', $conditions);

        // Paginação e dados
        $len_servicos  = Servico::getLenServicos($where);
        $objPagination = new Pagination($len_servicos, $_GET['p'] ?? 1, 5);
        $servicos      = Servico::getServicos($where, null, $objPagination->getLimit());
        break;

    // case 'agendamentos':
    //     ...
    //     break;
}

include __DIR__ . '/../app/Views/includes/header.php';
include __DIR__ . '/../app/Views/includes/navbar.php';

switch ($page) {

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