<?php

namespace App\Session;

class Login
{
    /**
     * Método responsável por iniciar a sessão
     */
    private static function initLogin() {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Método responsável por retornar os dados do usuário logado
     * @return [type]
     */
    public static function getUsuarioLoged() {
        self::initLogin();

        return self::isLogged() ? $_SESSION['user'] : null;
    }
    
    /**
     * Método responsável por logar o usuário
     * @param Usuario $objUsuario
     * @return [type]
     */
    public static function loginFunction($objUsuario) {
        self::initLogin();

        $_SESSION['user'] = [
            'id'         => $objUsuario->id,
            'name'       => $objUsuario->name,
            'surname'    => $objUsuario->surname,
            'email'      => $objUsuario->email,
            'phone_n'    => $objUsuario->phone_n,
            'pass'       => $objUsuario->pass,
            'role'       => $objUsuario->role,
            'created_at' => $objUsuario->create_at,
        ];

        //Redirecionar Usuário par index
        header('location: /');
        exit;
    }

    /**
     * Método responsável por deslogar o usuário
     */
    public static function logoutFunction() {
        self::initLogin();

        unset($_SESSION['user']);

        header('location: /login');
    }

    /**
     * Método responsável por vertificar se o usuário está loggado no sistema
     * @return bollean
     */
    public static function isLogged()
    {
        self::initLogin();


        return isset($_SESSION['user']['id']);
    }

    /**
     * Método responsável por definir a obrigação de login para acesso
     * @return [type]
     */
    public static function requrireLogin(){
        if(!self::isLogged()) {
            header('location: /login');
            exit;
        }
    }

    /**
     * Método responsável por definir a obrigação de Loggout para acesso
     * @return [type]
     */
    public static function requireLogout(){
        if(self::isLogged()) {
            header('location: /');
            exit;
        }
    }
}
