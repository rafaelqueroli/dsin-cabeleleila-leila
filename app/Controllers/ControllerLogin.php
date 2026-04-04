<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Session\Login;

/**
 * ControllerLogin
 * Gerencia o fluxo de autenticação e o auto-cadastro de novos usuários.
 */
class ControllerLogin
{
    /**
     * Exibe a tela de login/cadastro e processa as tentativas de autenticação.
     * Rota: /login
     */
    public function index(): void
    {
        // Garante que se o usuário já estiver logado, ele seja redirecionado para fora daqui
        Login::requireLogout();

        $alert_login    = '';
        $alert_register = '';

        // Processamento de formulários via POST
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {

                case 'login':
                    // Busca o usuário pelo e-mail único
                    $objUsuario = Usuario::getUsuariobyEmail($_POST['email']);

                    // Validação de credenciais: Usuário existe + Senha confere com o Hash
                    if (!($objUsuario instanceof Usuario) || !password_verify($_POST['pass'], $objUsuario->pass)) {
                        $alert_login = "Credenciais Inválidas";
                        break;
                    }

                    // Inicia a sessão oficial do usuário
                    Login::loginFunction($objUsuario);
                    break;

                case 'register':
                    // Validação de recebimento de todos os campos necessários para o cadastro
                    if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['pass'], $_POST['role'])) {
                        $objUsuario          = new Usuario;
                        $objUsuario->name    = $_POST['name'];
                        $objUsuario->surname = $_POST['surname'];
                        $objUsuario->email   = $_POST['email'];
                        $objUsuario->phone_n = $_POST['phone_n'];
                        
                        // Segurança: Criptografa a senha escolhida pelo usuário
                        $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                        $objUsuario->role    = $_POST['role'];

                        $objUsuario->registerUsuario();

                        header('location: /login?status=success');
                        exit;
                    }
                    break;
            }
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/login/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }
}