<?php

require __DIR__ . '/../vendor/autoload.php';

use App\DB\Pagination;
use App\Models\Usuario;
use App\Models\Servico;
use App\Models\Agendamento;
use App\Session\Login;

/** @var string|null Captura a página atual via parâmetro GET */
$page = $_GET['page'] ?? null;

/**
 * Controle de Acesso e Autenticação
 * Garante que apenas usuários autenticados acessem áreas restritas.
 */
if ($page !== 'login') {
    Login::requireLogin();
} else {
    // Renderização isolada da view de login
    include __DIR__ . '/../app/Views/includes/login.php';
    exit;
}

// Encerramento de Sessão
if ($page === 'logout') {
    Login::logoutFunction();
    exit;
}

/**
 * Roteamento de Ações (CRUD)
 * Captura a entidade (Model) e a ação (Método) para processamento via Controller.
 */
$entity = $_GET['entity'] ?? null;
$action = $_GET['action'] ?? null;

if ($action !== null) {
    match ($entity) {
        // Processamento de requisições de Usuários
        'usuario' => (function () use ($action) {
            $controller = new \App\Controllers\ControllerUsuario();
            match ($action) {
                'create'          => $controller->create(),
                'update'          => $controller->update(),
                'delete'          => $controller->delete(),
                'account'         => $controller->account(),
                'change-password' => $controller->changePassword(),
                default           => header('location: /'),
            };
        })(),

        // Processamento de requisições de Serviços
        'servico' => (function () use ($action) {
            $controller = new \App\Controllers\ControllerServico();
            match ($action) {
                'create' => $controller->create(),
                'update' => $controller->update(),
                'delete' => $controller->delete(),
                default  => header('location: /'),
            };
        })(),

        // Processamento de requisições de Agendamentos
        'agendamento' => (function () use ($action) {
            $controller = new \App\Controllers\ControllerAgendamento();
            match ($action) {
                'create' => $controller->create(),
                'update' => $controller->update(),
                'delete' => $controller->delete(),
                'cancel' => $controller->cancel(),
                default  => header('location: /'),
            };
        })(),

        default => header('location: /'),
    };
    exit;
}

/**
 * Orquestração de Dados para Views
 * Prepara as variáveis e filtros necessários antes da renderização final.
 */
switch ($page) {
    case 'users':
        /** Validação de permissão administrativa */
        $userLogado = Login::getUsuarioLoged();
        if ($userLogado['role'] !== 'a') {
            header('location: /?status=error');
            exit;
        }

        // Sanitização e definição de filtros de busca para usuários
        $search      = $_GET['search'] ?? '';
        $role_search = $_GET['role_search'] ?? '';
        $role_search = in_array($role_search, ['c', 'a']) ? $role_search : null;

        $conditions = array_filter([
            strlen($search)      ? 'name LIKE "%' . $search . '%" OR surname LIKE "%' . $search . '%"' : '',
            strlen($role_search) ? 'role = "' . $role_search . '"' : '',
        ]);
        $where = implode(' AND ', $conditions);

        // Lógica de Paginação e Recuperação de Coleção
        $len_usuarios  = Usuario::getLenUsuarios($where);
        $objPagination = new Pagination($len_usuarios, $_GET['p'] ?? 1, 5);
        $usuarios      = Usuario::getUsuarios($where, null, $objPagination->getLimit());
        break;

    case 'servicos':
        /** Validação de permissão administrativa */
        $userLogado = Login::getUsuarioLoged();
        if ($userLogado['role'] !== 'a') {
            header('location: /?status=error');
            exit;
        }

        // Filtros de busca para serviços
        $search     = $_GET['search'] ?? '';
        $cat_search = $_GET['cat_search'] ?? '';
        $cat_search = in_array($cat_search, ['c', 'u', 'e']) ? $cat_search : null;

        $conditions = array_filter([
            strlen($search)     ? 'name LIKE "%' . $search . '%"' : '',
            strlen($cat_search) ? 'cat = "' . $cat_search . '"' : '',
        ]);
        $where = implode(' AND ', $conditions);

        $len_servicos  = Servico::getLenServicos($where);
        $objPagination = new Pagination($len_servicos, $_GET['p'] ?? 1, 5);
        $servicos      = Servico::getServicos($where, null, $objPagination->getLimit());
        break;

    case 'agendamentos':
        $userLogado = Login::getUsuarioLoged();
        $isAdmin    = $userLogado['role'] === 'a';

        $search        = $_GET['search'] ?? '';
        $status_search = $_GET['status_search'] ?? '';
        $status_search = in_array($status_search, ['pendente', 'confirmado', 'concluido', 'cancelado']) ? $status_search : null;

        /** * Regras de Negócio para Listagem de Agendamentos:
         * 1. Admins visualizam um intervalo de 3 meses.
         * 2. Clientes visualizam apenas seus próprios agendamentos.
         */
        $conditions = array_filter([
            $isAdmin
                ? 'date BETWEEN "' . date('Y-m-01', strtotime('first day of last month')) . '" AND "' . date('Y-m-t', strtotime('last day of next month')) . '"'
                : 'cliente_id = ' . $userLogado['id'],

            $status_search ? 'status = "' . $status_search . '"' : '',

            ($isAdmin && strlen($search))
                ? 'cliente_id IN (SELECT id FROM tbUsuarios WHERE name LIKE "%' . $search . '%" OR surname LIKE "%' . $search . '%")'
                : ''
        ]);

        $where = implode(' AND ', $conditions);

        $len_agendamentos = Agendamento::getLenAgendamentos($where);
        $objPagination    = new Pagination($len_agendamentos, $_GET['p'] ?? 1, 5);
        $agendamentos     = Agendamento::getAgendamentos($where, 'date ASC, time_start ASC', $objPagination->getLimit());
        break;
}

/**
 * Renderização da Interface (View)
 * Composição do layout utilizando componentes de cabeçalho, navegação e rodapé.
 */
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
        include __DIR__ . '/../app/Views/includes/home.php';
}

include __DIR__ . '/../app/Views/includes/footer.php';
