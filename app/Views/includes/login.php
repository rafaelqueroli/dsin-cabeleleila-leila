<?php

require __DIR__ . '/../../../vendor/autoload.php';

use \App\Models\Usuario;
use \App\Session\Login;

Login::requireLogout();

// Mensagens de Alerta
$alert_login    = '';
$alert_register = '';

if (isset($_POST['action'])) {

    switch ($_POST['action']) {
        case 'login':

            // Busca Usuário por Email
            $objUsuario = Usuario::getUsuariobyEmail($_POST['email']);

            // Valida a Instânica e a Senha
            if (!$objUsuario instanceof Usuario || !password_verify($_POST['pass'], $objUsuario->pass)) {
                $alert_login = "Credenciais Inválidas";
                break;
            }

            Login::loginFunction($objUsuario);

            break;

        case 'register':
            if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['pass'], $_POST['role'])) {

                $objUsuario = new Usuario;
                $objUsuario->name    = $_POST['name'];
                $objUsuario->surname = $_POST['surname'];
                $objUsuario->email   = $_POST['email'];
                $objUsuario->phone_n = $_POST['phone_n'];
                $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                $objUsuario->role    = $_POST['role'];

                $objUsuario->registerUsuario();
            }
            break;
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/../forms/login/form.php';
include __DIR__ . '/footer.php';
