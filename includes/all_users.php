<?php



$resultados = '';
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

    $resultados .= '<tr>
                        <td>' . $usuario->id . '</td>
                        <td>' . $usuario->name . ' ' . $usuario->surname . '</td>
                        <td>' . $usuario->email . '</td>
                        <td>' . $usuario->phone_n . '</td>
                        <td>' . $retRole . '</td>
                        <td>' . date('d/m/Y à\s H:i:s', strtotime($usuario->created_at)) . '</td>
                        <td></td>
                    </tr>
        ';
}

?>

<section>
    <a href="new_user.php">
        <div class="d-grid mb-5 mt-5">
            <button class="btn btn-primary" type="button">Registrar Usuário</button>
        </div>
    </a>
</section>

<section>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome Completo</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Função</th>
                <th>Criada em: </th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <?= $resultados ?>
            </tr>
        </tbody>
    </table>
</section>