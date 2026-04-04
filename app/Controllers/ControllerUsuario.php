<?php

// Definição do namespace pro autoload
namespace App\Controllers;

// Declaração do objeto Usuário
use App\Models\Usuario;
use App\Session\Login;

class ControllerUsuario
{

    /**
     * Formulário de Cadastro
     * Função responsável pelo cadastro de um usuário dentro do Banco de Dados
     * @return void
     */
    public function create(): void
    {
        // Definição do Título do Formulário
        define('TITLE', 'Registrar Usuário');

        $objUsuario = new Usuario();

        // POST dos dados do Usuário
        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $objUsuario->role    = $_POST['role'];

            $objUsuario->registerUsuario();

            header('location: /usuarios?status=success');
            exit;
        }

        // View do Formulário
        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Formulário de Edição
     * Função responsável pelo edição dos dados de um usuário dentro do Banco de Dados
     * Rota na URL: /usuários/editar/['id']
     * @return void
     */
    public function update(): void
    {
        // Definição do Título do Formulário
        define('TITLE', 'Editar Usuário');

        // Se o Id do usuário não for encontrado, ou, se o Id não for um número, imprimir erro!
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /usuarios?status=error');
            exit;
        }

        // Método GET para resgatar os dados de um usuário já registrado
        $objUsuario = Usuario::getUsuario($_GET['id']);

        // Se objeto Usuário não for instaância da classe Usuário, imprimir erro!
        if (!$objUsuario instanceof Usuario) {
            header('location: /usuarios?status=error');
            exit;
        }

        // POST dos dados atualizados do Usuário
        if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone_n'], $_POST['role'])) {
            $objUsuario->name    = $_POST['name'];
            $objUsuario->surname = $_POST['surname'];
            $objUsuario->email   = $_POST['email'];
            $objUsuario->phone_n = $_POST['phone_n'];
            $objUsuario->pass    = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $objUsuario->role    = $_POST['role'];

            $objUsuario->updateUsuario();

            header('location: /usuarios?status=success');
            exit;
        }

        // View do Formulário
        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Deletar Usuário
     * Função responsável pela exclusão dos dados de um usuário dentro do Banco de Dados
     * Rota na URL: /usuários/excluir/['id']
     * @return void
     */
    public function delete(): void
    {
        // Se o Id do usuário não for encontrado, ou, se o Id não for um número, imprimir erro!
        if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
            header('location: /usuarios?status=error');
            exit;
        }

        $objUsuario = Usuario::getUsuario($_GET['id']);


        // Se objeto Usuário não for instaância da classe Usuário, imprimir erro!
        if (!$objUsuario instanceof Usuario) {
            header('location: /usuarios?status=error');
            exit;
        }

        // Se for delete for confirmado, o Usuário é excluído.
        if (isset($_POST['delete'])) {

            $objUsuario->deleteUsuario();

            header('location: /usuarios?status=success');
            exit;
        }

        // View do Formulário - Confirmação de Exclusão do Usuário
        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/usuario/alert_delete.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }
}
