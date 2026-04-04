<?php

namespace App\Session;

class Login
{
    /**
     * Garante que a sessão do PHP esteja ativa antes de qualquer operação.
     * @return void
     */
    private static function initLogin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Retorna os dados do usuário armazenados na sessão.
     * @return array|null
     */
    public static function getUsuarioLoged()
    {
        self::initLogin();
        return self::isLogged() ? $_SESSION['user'] : null;
    }

    /**
     * Cria a sessão do usuário após validação de credenciais no Controller.
     * @param \App\Models\Usuario $objUsuario
     */
    public static function loginFunction($objUsuario)
    {
        self::initLogin();

        // Armazena apenas o necessário para identificar e validar permissões
        $_SESSION['user'] = [
            'id'         => $objUsuario->id,
            'name'       => $objUsuario->name,
            'surname'    => $objUsuario->surname,
            'email'      => $objUsuario->email,
            'phone_n'    => $objUsuario->phone_n,
            'role'       => $objUsuario->role, // 'a' para admin, 'c' para cliente
            'created_at' => $objUsuario->create_at,
        ];

        header('location: /');
        exit;
    }

    /**
     * Atualiza os dados da sessão em tempo real.
     * Útil quando o usuário altera o próprio nome ou telefone no perfil.
     */
    public static function updateSession($objUsuario)
    {
        self::initLogin();

        $_SESSION['user'] = [
            'id'         => $objUsuario->id,
            'name'       => $objUsuario->name,
            'surname'    => $objUsuario->surname,
            'email'      => $objUsuario->email,
            'phone_n'    => $objUsuario->phone_n,
            'role'       => $objUsuario->role,
            'created_at' => $objUsuario->create_at,
        ];
    }

    /**
     * Encerra a sessão e limpa os dados do usuário.
     */
    public static function logoutFunction()
    {
        self::initLogin();
        unset($_SESSION['user']);
        header('location: /login');
        exit;
    }

    /**
     * Verifica se existe um usuário autenticado na sessão atual.
     * @return bool
     */
    public static function isLogged()
    {
        self::initLogin();
        return isset($_SESSION['user']['id']);
    }

    /**
     * Trava de segurança: Redireciona para o login se o usuário não estiver autenticado.
     * Ideal para proteger rotas de agendamento e perfil.
     */
    public static function requireLogin()
    {
        if (!self::isLogged()) {
            header('location: /login');
            exit;
        }
    }

    /**
     * Impede que um usuário já logado acesse a página de login ou cadastro.
     */
    public static function requireLogout()
    {
        if (self::isLogged()) {
            header('location: /');
            exit;
        }
    }
}
