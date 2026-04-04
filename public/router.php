<?php

// Captura apenas o caminho da URL (excluindo query strings)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

/**
 * Verificação de arquivos estáticos.
 * Se a URI apontar para um arquivo real (CSS, JS, Imagem), o roteador 
 * retorna 'false' para permitir que o servidor web o sirva diretamente.
 */
if (is_file($file)) {
    return false;
}

// Remove a barra final da URI para padronização do roteamento
$uri = rtrim($uri, '/');

/**
 * Mapeamento de Rotas Dinâmicas (com IDs).
 * Utiliza Expressões Regulares (Regex) para identificar padrões e capturar parâmetros numéricos.
 */
if (preg_match('#^/usuarios/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'usuario';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/usuarios/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'usuario';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/servicos/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'servico';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/servicos/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'servico';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/agendamentos/editar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'update';
    $_GET['entity'] = 'agendamento';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/agendamentos/cancelar/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'cancel';
    $_GET['entity'] = 'agendamento';
    $_GET['id']     = $matches[1];
} elseif (preg_match('#^/agendamentos/excluir/(\d+)$#', $uri, $matches)) {
    $_GET['action'] = 'delete';
    $_GET['entity'] = 'agendamento';
    $_GET['id']     = $matches[1];

    /**
     * Mapeamento de Rotas Estáticas.
     * Define a página, entidade ou ação com base em URIs fixas.
     */
} else {
    switch ($uri) {
        case '':
        case '/':
            $_GET['page'] = 'home';
            break;

        case '/login':
            $_GET['page'] = 'login';
            break;

        case '/logout':
            $_GET['page'] = 'logout';
            break;

        case '/usuarios':
            $_GET['page'] = 'users';
            break;
        case '/usuarios/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'usuario';
            break;

        case '/servicos':
            $_GET['page'] = 'servicos';
            break;
        case '/servicos/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'servico';
            break;

        case '/agendamentos':
            $_GET['page'] = 'agendamentos';
            break;
        case '/agendamentos/novo':
            $_GET['action'] = 'create';
            $_GET['entity'] = 'agendamento';
            break;

        case '/minha-conta':
            $_GET['action'] = 'account';
            $_GET['entity'] = 'usuario';
            break;
        case '/minha-conta/senha':
            $_GET['action'] = 'change-password';
            $_GET['entity'] = 'usuario';
            break;

        /**
         * Tratamento de Erro 404.
         * Acionado quando nenhuma rota corresponde à URI solicitada.
         */
        default:
            http_response_code(404);
            echo '404 - Página não encontrada';
            exit;
    }
}

/**
 * Redirecionamento Final.
 * Após a definição dos parâmetros $_GET via roteador, o index.php 
 * assume o controle para processar a lógica de negócio.
 */
require __DIR__ . '/index.php';
