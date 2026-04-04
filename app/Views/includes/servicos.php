<?php
// Variáveis vindas do index.php:
// $servicos, $objPagination, $search, $cat_search

$message = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success':
            $message = '<div class="alert alert-success mb-3 text-center"><i class="bi bi-check-circle me-2"></i>Ação executada com sucesso!</div>';
            break;
        case 'error':
            $message = '<div class="alert alert-danger mb-3 text-center"><i class="bi bi-x-circle me-2"></i>Ação não executada!</div>';
            break;
    }
}

$catLabel = [
    'c' => '<span class="badge bg-primary">Cabelos</span>',
    'u' => '<span class="badge bg-success">Unhas</span>',
    'e' => '<span class="badge bg-info text-dark">Estética</span>',
];
?>

<section>
    <a href="/servicos/novo">
        <div class="d-grid mb-3">
            <button class="btn btn-primary" type="button">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Serviço
            </button>
        </div>
    </a>
</section>

<section class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="get">
            <input type="hidden" name="page" value="servicos">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Buscar por Nome</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Nome do serviço..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filtrar por Categoria</label>
                    <select name="cat_search" class="form-select">
                        <option value="">Todas as Categorias</option>
                        <option value="c" <?= ($cat_search ?? '') == 'c' ? 'selected' : '' ?>>Cabelos</option>
                        <option value="u" <?= ($cat_search ?? '') == 'u' ? 'selected' : '' ?>>Unhas</option>
                        <option value="e" <?= ($cat_search ?? '') == 'e' ? 'selected' : '' ?>>Estética</option>
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

<h2 class="text-center mb-4">Lista de Serviços</h2>

<section class="table-responsive">
    <table class="table align-middle shadow table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Duração (min)</th>
                <th>Preço (R$)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($servicos)): ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum serviço encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($servicos as $servico): ?>
                    <tr class="text-center">
                        <td><?= $servico->id ?></td>
                        <td><?= htmlspecialchars($servico->name) ?></td>
                        <td><?= $catLabel[$servico->cat] ?? $servico->cat ?></td>
                        <td><?= $servico->duration_min ?> min</td>
                        <td>R$ <?= number_format($servico->price, 2, ',', '.') ?></td>
                        <td>
                            <a href="/servicos/editar/<?= $servico->id ?>" title="Editar">
                                <button class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></button>
                            </a>
                            <a href="/servicos/excluir/<?= $servico->id ?>" title="Excluir">
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