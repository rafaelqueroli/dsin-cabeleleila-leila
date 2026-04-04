<?php

namespace App\Controllers;

use App\Models\Servico;

class ControllerServico
{
    /**
     * Exibe o formulário e processa o cadastro de um novo serviço.
     * Rota amigável: /servicos/novo
     */
    public function create(): void
    {
        // Define o título da página para ser capturado no header.php
        define('TITLE', 'Criar Serviço');

        $objServico = new Servico();

        // Verifica se os campos obrigatórios foram enviados via POST
        if (isset($_POST['cat'], $_POST['name'], $_POST['duration_min'], $_POST['price'])) {
            $objServico->cat          = $_POST['cat'];
            $objServico->name         = $_POST['name'];
            $objServico->duration_min = $_POST['duration_min'];
            $objServico->price        = $_POST['price'];

            $objServico->registerServico();

            // Redireciona com flag de sucesso para feedback visual
            header('location: /servicos?status=success');
            exit;
        }

        $this->renderView('forms/servico/form.php', ['objServico' => $objServico]);
    }

    /**
     * Exibe o formulário pré-preenchido e processa a edição de um serviço.
     * Rota amigável: /servicos/editar/{id}
     */
    public function update(): void
    {
        define('TITLE', 'Editar Serviço');

        // Validação de segurança: o ID deve estar presente e ser numérico
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /servicos?status=error');
            exit;
        }

        $objServico = Servico::getServico($_GET['id']);

        // Garante que o serviço existe no banco de dados
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

        $this->renderView('forms/servico/form.php', ['objServico' => $objServico]);
    }

    /**
     * Processa a exclusão de um serviço após confirmação do usuário.
     * Rota amigável: /servicos/excluir/{id}
     */
    public function delete(): void
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /servicos?status=error');
            exit;
        }

        $objServico = Servico::getServico($_GET['id']);

        if (!$objServico instanceof Servico) {
            header('location: /servicos?status=error');
            exit;
        }

        // A exclusão só ocorre se o usuário confirmar no formulário de alerta (POST)
        if (isset($_POST['delete'])) {
            $objServico->deleteServico();

            header('location: /servicos?status=success');
            exit;
        }

        $this->renderView('forms/servico/alert_delete.php', ['objServico' => $objServico]);
    }

    /**
     * Método auxiliar para renderizar as views.
     * Facilita a manutenção evitando a repetição manual de header/footer/navbar.
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
