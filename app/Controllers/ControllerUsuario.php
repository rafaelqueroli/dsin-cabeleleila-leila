<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Session\Login;

class ControllerUsuario
{
    /** * @var string Senha padrão para novos usuários criados pelo administrador.
     */
    private static string $PROVISIONAL_PASS = 'senha123';

    /**
     * Cria um novo usuário no sistema.
     * Gera uma senha provisória criptografada para o primeiro acesso.
     */
    public function create(): void
    {
        define('TITLE', 'Registrar Usuário');
        $objUsuario = new Usuario();

        // Validação básica de presença dos dados via POST
        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->role    = $_POST['role'];
            
            // Segurança: Armazenamento de senha utilizando BCRYPT (padrão do PHP)
            $objUsuario->pass    = password_hash(self::$PROVISIONAL_PASS, PASSWORD_DEFAULT);

            $objUsuario->registerUsuario();

            header('location: /usuarios?status=success&provisional=1');
            exit;
        }

        $this->renderView('forms/usuario/form.php');
    }

    /**
     * Atualiza dados de um usuário existente (Acesso Administrativo).
     */
    public function update(): void
    {
        define('TITLE', 'Editar Usuário');

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /usuarios?status=error');
            exit;
        }

        $objUsuario = Usuario::getUsuario($_GET['id']);

        if (!$objUsuario instanceof Usuario) {
            header('location: /usuarios?status=error');
            exit;
        }

        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->role    = $_POST['role'];

            // Atualização opcional de senha: só processa se o campo for preenchido
            if (!empty($_POST['pass'])) {
                $objUsuario->pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            $objUsuario->updateUsuario();

            header('location: /usuarios?status=success');
            exit;
        }

        $this->renderView('forms/usuario/form.php', ['objUsuario' => $objUsuario]);
    }

    /**
     * Remove um usuário do sistema após confirmação.
     */
    public function delete(): void
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /usuarios?status=error');
            exit;
        }

        $objUsuario = Usuario::getUsuario($_GET['id']);

        if (!$objUsuario instanceof Usuario) {
            header('location: /usuarios?status=error');
            exit;
        }

        if (isset($_POST['delete'])) {
            $objUsuario->deleteUsuario();
            header('location: /usuarios?status=success');
            exit;
        }

        $this->renderView('forms/usuario/alert_delete.php', ['objUsuario' => $objUsuario]);
    }

    /**
     * Permite ao usuário logado gerenciar suas próprias informações.
     * Exige a senha atual para validação de identidade (Segurança).
     */
    public function account(): void
    {
        $userLogado = Login::getUsuarioLoged();
        $objUsuario = Usuario::getUsuario($userLogado['id']);
        $alert      = '';

        if (!$objUsuario instanceof Usuario) {
            header('location: /?status=error');
            exit;
        }

        if (isset($_POST['name'], $_POST['surname'], $_POST['phone_n'], $_POST['pass_confirm'])) {
            // Validação de senha antes de permitir a alteração dos dados
            if (!password_verify($_POST['pass_confirm'], $objUsuario->pass)) {
                $alert = 'error-pass';
            } else {
                $objUsuario->name    = $_POST['name'];
                $objUsuario->surname = $_POST['surname'];
                $objUsuario->phone_n = $_POST['phone_n'];

                $objUsuario->updateUsuario();
                Login::updateSession($objUsuario); // Sincroniza os dados na sessão ativa

                header('location: /minha-conta?status=success');
                exit;
            }
        }

        define('TITLE', 'Minha Conta');
        $this->renderView('forms/usuario/form_account.php', ['objUsuario' => $objUsuario, 'alert' => $alert]);
    }

    /**
     * Processa a troca de senha do usuário autenticado.
     */
    public function changePassword(): void
    {
        $userLogado = Login::getUsuarioLoged();
        $objUsuario = Usuario::getUsuario($userLogado['id']);
        $alert      = '';

        if (!$objUsuario instanceof Usuario) {
            header('location: /?status=error');
            exit;
        }

        if (isset($_POST['pass_old'], $_POST['pass_new'], $_POST['pass_confirm'])) {
            if (!password_verify($_POST['pass_old'], $objUsuario->pass)) {
                $alert = 'error-old';
            } elseif ($_POST['pass_new'] !== $_POST['pass_confirm']) {
                $alert = 'error-match';
            } else {
                $objUsuario->pass = password_hash($_POST['pass_new'], PASSWORD_DEFAULT);
                $objUsuario->updateUsuario();
                Login::updateSession($objUsuario);

                header('location: /minha-conta?status=success');
                exit;
            }
        }

        define('TITLE', 'Alterar Senha');
        $this->renderView('forms/usuario/form-change-password.php', ['alert' => $alert]);
    }

    /**
     * Método auxiliar para centralizar a renderização de views com header/footer.
     */
    private function renderView(string $viewPath, array $data = [])
    {
        extract($data);

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/includes/navbar.php';
        include __DIR__ . "/../Views/{$viewPath}";
        include __DIR__ . '/../Views/includes/footer.php';
    }
}