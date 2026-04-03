<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

if (is_file($file)) {
    return false; // PHP built-in server serve o arquivo diretamente
}

$uri = rtrim($uri, '/');

// --- Rotas com ID ---

// Usuários
if (preg_match('#^/usuarios/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'usuario';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/usuarios/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'usuario';
    $_GET['id']     = $matches[1];

    // Serviços
} elseif (preg_match('#^/servicos/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'servico';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/servicos/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'servico';
    $_GET['id']     = $matches[1];

    // Agendamentos
} elseif (preg_match('#^/agendamentos/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'agendamento';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/agendamentos/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'agendamento';
    $_GET['id']     = $matches[1];

    // --- Rotas simples ---
} else {
    switch ($uri) {
        case '':
        case '/':
            $_GET['page'] = 'home';
            break;

        case '/usuarios':
            $_GET['page']   = 'users';
            break;
        case '/usuarios/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'usuario';
            break;

        case '/servicos':
            $_GET['page']   = 'servicos';
            break;
        case '/servicos/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'servico';
            break;

        case '/agendamentos':
            $_GET['page']   = 'agendamentos';
            break;
        case '/agendamentos/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'agendamento';
            break;

        default:
            http_response_code(404);
            echo '404 - Página não encontrada';
            exit;
    }
}

require __DIR__ . '/index.php';
