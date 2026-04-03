<?php

namespace App\Controllers;

use App\Models\Usuario;

class ControllerUsuario
{
    /**
     * Exibe o formulário e processa o cadastro de um novo usuário.
     * Rota: index.php?action=novo
     */
    public function create(): void
    {
        define('TITLE', 'Registrar Usuário');

        $objUsuario = new Usuario();

        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $objUsuario->role    = $_POST['role'];

            $objUsuario->registerUsuario();

            header('location: index.php?page=users&status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Exibe o formulário e processa a edição de um usuário existente.
     * Rota: index.php?action=editar&id=1
     */
    public function update(): void
    {
        define('TITLE', 'Editar Usuário');

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: index.php?page=users&status=error');
            exit;
        }

        $objUsuario = Usuario::getUsuario($_GET['id']);

        if (!$objUsuario instanceof Usuario) {
            header('location: index.php?page=users&status=error');
            exit;
        }

        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $objUsuario->role    = $_POST['role'];

            $objUsuario->updateUsuario();

            header('location: index.php?page=users&status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Processa a exclusão de um usuário.
     * Rota: index.php?action=excluir&id=1
     */
    public function delete(): void
    {
        if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
            header('location: index.php?page=users&status=error');
            exit;
        }

        $objUsuario = Usuario::getUsuario($_GET['id']);


        // Validação do Usuário
        if (!$objUsuario instanceof Usuario) {
            header('location: index.php?page=users&status=error');
            exit;
        }

        // Validação do Post
        if (isset($_POST['delete'])) {

            $objUsuario->deleteUsuario();

            header('location: index.php?page=users&status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/alert_delete.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }
}
