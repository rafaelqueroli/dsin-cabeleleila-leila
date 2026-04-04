<?php

$message = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            $message = '<div class="alert alert-success mb-3 text-center">Ação executada com sucesso!</div>';
            break;

        case 'error':
            $message = '<div class="alert alert-danger mb-3 text-center">Ação não executada!</div>';
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

//Gets
unset($_GET['status']);
unset($_GET['p']);

$gets = http_build_query($_GET);

// Paginação
$pagination = '';
$pages = $objPagination->getPages();

foreach ($pages as $key => $p) {
    $class = $p['current'] ? 'btn-primary' : 'btn-light';
    $pagination .= '<a href="?p=' . $p['p'] . '&' . $gets .  '">
                        <button type="button" class="btn ' . $class . '">' . $p['p'] . '</button>
                    </a>';
}

?>

<section>
    <a href="/servicos/novo">
        <div class="d-grid mb-3">
            <button class="btn btn-primary" type="button">Adicionar Serviço</button>
        </div>
    </a>
</section>

<?= $message ?>

<section class="mb-5">
    <form method="get">
        <div class="row g-2 mb-3">
            <div class="col-8">
                <label class="form-label">Buscar por Nome</label>
                <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-4">
                <label class="form-label">Filtrar por Categoria</label>
                <select name="cat_search" class="form-select">
                    <option value = "" selected>Todas as Categorias</option>
                    <option value = "c" <?= $cat_search == 'c' ? 'selected' : '' ?>>Cabelo</option>
                    <option value = "u" <?= $cat_search == 'u' ? 'selected' : '' ?>>Unhas</option>
                    <option value = "e" <?= $cat_search == 'e' ? 'selected' : '' ?>>Estética</option>
                </select>
            </div>
        </div>

        <div class="row g-2 d-grid">
            <div class="col d-grid align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
</section>

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

<section class="row d-grid justify-content-center">
    <div class="col">
        <?= $pagination ?>
    </div>
</section>