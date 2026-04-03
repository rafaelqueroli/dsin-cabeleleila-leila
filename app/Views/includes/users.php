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
foreach ($usuarios as $usuario) {
    switch ($usuario->role) {
        case 'c':
            $retRole = 'Cliente';
            break;
        case 'f':
            $retRole = 'Funcionário';
            break;
        case 'a':
            $retRole = 'Admin';
            break;
    }

    $res .= '<tr>
                <td class="text-center">' . $usuario->id . '</td>
                <td class="text-center">' . $usuario->name . ' ' . $usuario->surname . '</td>
                <td class="text-center">' . $usuario->email . '</td>
                <td class="text-center">' . $usuario->phone_n . '</td>
                <td class="text-center">' . $retRole . '</td>
                <td class="text-center">' . date('d/m/Y à\s H:i:s', strtotime($usuario->created_at)) . '</td>
                <td class="text-center">
                    <a href="/usuarios/editar/' . $usuario->id . '">    
                        <button class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                    </a>
                    <a href="/usuarios/excluir/' . $usuario->id . '">
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

// echo '<pre>';
// print_r($pages);
// echo '</pre>';
?>

<section>
    <a href="/usuarios/novo">
        <div class="d-grid mb-3">
            <button class="btn btn-primary" type="button">Registrar Usuário</button>
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
                <label class="form-label">Filtrar Função</label>
                <select name="role_search" class="form-select">
                    <option value="" selected>Todas as Funções</option>
                    <option value="c" <?= $role_search == 'c' ? 'selected' : '' ?>>Cliente</option>
                    <option value="f" <?= $role_search == 'f' ? 'selected' : '' ?>>Funcionário</option>
                    <option value="a" <?= $role_search == 'a' ? 'selected' : '' ?>>Admin</option>
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

<h2 class="text-center">Lista de Usuários</h2>

<section class="table-responsive">

    <table class="table align-middle shadow table-striped table-hover">

        <thead>
            <tr class="align-top text-center">
                <th>ID</th>
                <th>Nome Completo</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Função</th>
                <th>Criada em</th>
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