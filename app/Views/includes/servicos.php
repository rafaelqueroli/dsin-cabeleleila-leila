<?php

$message = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            $message = '<div class="alert alert-success mb-3">Ação executada com sucesso!</div>';
            break;

        case 'error':
            $message = '<div class="alert alert-danger mb-3">Ação não executada!</div>';
            break;
    }
}

$res = '';
foreach ($servicos as $servico) {
    switch ($servico->cat) {
        case 'c':
            $retCat = 'Cabelos';
            break;
        case 'u':
            $retCat = 'Unhas';
            break;
        case 'e':
            $retCat = 'Estética';
            break;
    }

    $res .= '<tr>
                <td class="text-center">' . $servico->id . '</td>
                <td class="text-center">' . $servico->name . '</td>
                <td class="text-center">' . $retCat . '</td>
                <td class="text-center">' . $servico->duration_min . '</td>
                <td class="text-center">' . $servico->price . '</td>
                <td class="text-center">
                    <a href="/servicos/editar/' . $servico->id . '">    
                        <button class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                    </a>
                    <a href="/servicos/excluir/' . $servico->id . '">
                        <button class="btn btn-danger"><i class="bi bi-trash-fill"></i></button>
                    </a>
                </td>
            </tr>';
}

$res = strlen($res) ? $res :    '<tr>
                                    <td colspan="6" class="text-center">Nenhum usuário encontrado</td>
                                </tr>';

?>

<section>
    <a href="/servicos/novo">
        <div class="d-grid mb-5 mt-5">
            <button class="btn btn-primary" type="button">Adicionar Serviço</button>
        </div>
    </a>
</section>

<?= $message ?>

<h2 class="text-center">Lista de Serviços</h2>

<section class="table-responsive">

    <table class="table align-middle shadow table-striped table-hover">

        <thead>
            <tr class="align-top text-center">
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Duração (min)</th>
                <th>Preço (R$)</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <?= $res ?>
            </tr>
        </tbody>
    </table>
</section>