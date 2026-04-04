<?php
// Variáveis vindas do index.php:
// $usuarios, $objPagination, $search, $role_search

$message = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            // Mensagem especial quando o admin cria um usuário com senha provisória
            $message = isset($_GET['provisional'])
                ? '<div class="alert alert-success mb-3 text-center"><i class="bi bi-check-circle me-2"></i>Usuário registrado! A senha provisória é <strong>senha123</strong>. Informe o usuário para alterá-la.</div>'
                : '<div class="alert alert-success mb-3 text-center"><i class="bi bi-check-circle me-2"></i>Ação executada com sucesso!</div>';
            break;
        case 'error':
            $message = '<div class="alert alert-danger mb-3 text-center"><i class="bi bi-x-circle me-2"></i>Ação não executada!</div>';
            break;
    }
}

$roleLabel = [
    'c' => '<span class="badge bg-primary">Cliente</span>',
    'f' => '<span class="badge bg-info text-dark">Funcionário</span>',
    'a' => '<span class="badge bg-dark">Admin</span>',
];
?>

<section>
    <a href="/usuarios/novo">
        <div class="d-grid mb-3">
            <button class="btn btn-primary" type="button">
                <i class="bi bi-person-plus me-1"></i> Registrar Usuário
            </button>
        </div>
    </a>
</section>

<section class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="get">
            <input type="hidden" name="page" value="users">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Buscar por Nome</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Nome ou sobrenome..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filtrar Função</label>
                    <select name="role_search" class="form-select">
                        <option value="">Todas as Funções</option>
                        <option value="c" <?= ($role_search ?? '') == 'c' ? 'selected' : '' ?>>Cliente</option>
                        <option value="a" <?= ($role_search ?? '') == 'a' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<?= $message ?>

<h2 class="text-center mb-4">Lista de Usuários</h2>

<section class="table-responsive">
    <table class="table align-middle shadow table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Nome Completo</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Função</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr class="text-center">
                        <td><?= $usuario->id ?></td>
                        <td><?= htmlspecialchars($usuario->name . ' ' . $usuario->surname) ?></td>
                        <td><?= htmlspecialchars($usuario->email) ?></td>
                        <td><?= htmlspecialchars($usuario->phone_n) ?></td>
                        <td><?= $roleLabel[$usuario->role] ?? $usuario->role ?></td>
                        <td><?= date('d/m/Y \à\s H:i', strtotime($usuario->created_at)) ?></td>
                        <td>
                            <a href="/usuarios/editar/<?= $usuario->id ?>" title="Editar">
                                <button class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></button>
                            </a>
                            <a href="/usuarios/excluir/<?= $usuario->id ?>" title="Excluir">
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
$pages = $objPagination->getPages();
if (!empty($pages)):
?>
    <section class="row d-grid justify-content-center mt-3">
        <div class="col">
            <?php foreach ($pages as $p):
                $class = $p['current'] ? 'btn-primary' : 'btn-light';
            ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['p' => $p['p']])) ?>">
                    <button type="button" class="btn <?= $class ?>"><?= $p['p'] ?></button>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>