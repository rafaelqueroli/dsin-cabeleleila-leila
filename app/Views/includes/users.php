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
                    <a href="index.php?page=users&action=update&id=' . $usuario->id . '">    
                        <button class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                    </a>
                    <a href="index.php?page=users&action=delete&id=' . $usuario->id . '">
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
    <a href="index.php?users=page&action=create">
        <div class="d-grid mb-5 mt-5">
            <button class="btn btn-primary" type="button">Registrar Usuário</button>
        </div>
    </a>
</section>

<?= $message ?>

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