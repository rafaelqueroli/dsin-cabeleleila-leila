<?php

namespace App\Controllers;

use App\Models\Servico;
use App\Session\Login;

// Obriga o usuário a estar loggado
Login::requrireLogin();

class ControllerServico
{
    /**
     * Exibe o formulário e processa o cadastro de um novo usuário.
     * Rota: index.php?action=novo
     */
    public function create(): void
    {
        define('TITLE', 'Criar Serviço');

        $objServico = new Servico();

        if (isset($_POST['cat'], $_POST['name'], $_POST['duration_min'], $_POST['price'])) {
            $objServico->cat          = $_POST['cat'];
            $objServico->name         = $_POST['name'];
            $objServico->duration_min = $_POST['duration_min'];
            $objServico->price        = $_POST['price'];

            $objServico->registerServico();

            header('location: /servicos?status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/servico/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Exibe o formulário e processa a edição de um usuário existente.
     * Rota: index.php?action=editar&id=1
     */
    public function update(): void
    {
        define('TITLE', 'Editar Serviço');

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /servicos?status=error');
            exit;
        }

        $objServico = Servico::getServico($_GET['id']);

        if (!$objServico instanceof Servico) {
            header('location: /servicos?status=error');
            exit;
        }

        if (isset($_POST['cat'], $_POST['name'], $_POST['duration_min'], $_POST['price'])) {
            $objServico->cat          = $_POST['cat'];
            $objServico->name         = $_POST['name'];
            $objServico->duration_min = $_POST['duration_min'];
            $objServico->price        = $_POST['price'];

            $objServico->updateServico();

            header('location: /servicos?status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/servico/form.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }

    /**
     * Processa a exclusão de um usuário.
     * Rota: index.php?action=excluir&id=1
     */
    public function delete(): void
    {
        if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
            header('location: /servicos?status=error');
            exit;
        }

        $objServico = Servico::getServico($_GET['id']);


        // Validação do Usuário
        if (!$objServico instanceof Servico) {
            header('location: /servicos?status=error');
            exit;
        }

        // Validação do Post
        if (isset($_POST['delete'])) {

            $objServico->deleteServico();

            header('location: /servicos?status=success');
            exit;
        }

        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/forms/servico/alert_delete.php';
        include __DIR__ . '/../Views/includes/footer.php';
    }
}
