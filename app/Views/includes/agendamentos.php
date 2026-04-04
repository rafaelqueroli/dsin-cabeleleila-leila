<?php
// Variáveis vindas do index.php:
// $agendamentos, $objPagination, $userLogado, $isAdmin

$message = '';
if (isset($_GET['status'])) {
    $message = $_GET['status'] === 'success'
        ? '<div class="alert alert-success text-center">Ação executada com sucesso!</div>'
        : '<div class="alert alert-danger text-center">Ação não executada!</div>';
}

$statusLabel = [
    'pendente'    => '<span class="badge bg-warning text-dark">Pendente</span>',
    'confirmado'  => '<span class="badge bg-success">Confirmado</span>',
    'concluido'   => '<span class="badge bg-secondary">Concluído</span>',
    'cancelado'   => '<span class="badge bg-danger">Cancelado</span>',
];
?>

<section>
    <a href="/agendamentos/novo">
        <div class="d-grid mb-3">
            <button class="btn btn-primary" type="button">
                <i class="bi bi-plus-circle"></i>
                <?= $isAdmin ? 'Novo Agendamento' : 'Solicitar Agendamento' ?>
            </button>
        </div>
    </a>
</section>

<?php if ($isAdmin): ?>
    <section class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get">
                <input type="hidden" name="page" value="agendamentos">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Buscar por Cliente</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nome ou sobrenome..." 
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status_search" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="pendente"   <?= ($status_search ?? '') == 'pendente'   ? 'selected' : '' ?>>Pendente</option>
                            <option value="confirmado" <?= ($status_search ?? '') == 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                            <option value="concluido"  <?= ($status_search ?? '') == 'concluido'  ? 'selected' : '' ?>>Concluído</option>
                            <option value="cancelado"  <?= ($status_search ?? '') == 'cancelado'  ? 'selected' : '' ?>>Cancelado</option>
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
<?php endif; ?>

<?= $message ?>

<h2 class="text-center mb-4">
    <?= $isAdmin ? 'Todos os Agendamentos' : 'Meus Agendamentos' ?>
</h2>

<section class="table-responsive">
    <table class="table align-middle shadow table-striped table-hover">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <?php if ($isAdmin): ?><th>Cliente</th><?php endif; ?>
                <th>Data</th>
                <th>Início</th>
                <th>Término</th>
                <th>Serviços</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($agendamentos)): ?>
                <tr>
                    <td colspan="<?= $isAdmin ? 9 : 8 ?>" class="text-center">Nenhum agendamento encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($agendamentos as $ag):
                    $svcs       = \App\Models\Agendamento::getServicosDoAgendamento($ag->id);
                    $total      = \App\Models\Agendamento::getTotalPrice($ag->id);
                    $nomesSvcs  = implode(', ', array_column($svcs, 'name'));
                    $cliente    = $isAdmin ? \App\Models\Usuario::getUsuario($ag->cliente_id) : null;

                    $dataHora   = new \DateTime($ag->date . ' ' . $ag->time_start);
                    $agora      = new \DateTime();
                    $diff       = $agora->diff($dataHora);
                    $horas      = ($diff->days * 24) + $diff->h;
                    $bloqueado  = ($dataHora <= $agora || $horas < 48);
                ?>
                    <tr class="text-center">
                        <td><?= $ag->id ?></td>
                        <?php if ($isAdmin): ?>
                            <td><?= $cliente ? htmlspecialchars($cliente->name . ' ' . $cliente->surname) : '—' ?></td>
                        <?php endif; ?>
                        <td><?= date('d/m/Y', strtotime($ag->date)) ?></td>
                        <td><?= substr($ag->time_start, 0, 5) ?></td>
                        <td><?= substr($ag->time_end, 0, 5) ?></td>
                        <td class="text-start" style="max-width:200px; white-space:normal;">
                            <small><?= htmlspecialchars($nomesSvcs) ?></small>
                        </td>
                        <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
                        <td><?= $statusLabel[$ag->status] ?? $ag->status ?></td>
                        <td>
                            <?php
                            // Lógica de bloqueio: 
                            // 1. Se estiver cancelado, bloqueia para user comum.
                            // 2. Se estiver no prazo de 48h, bloqueia para user comum.
                            $statusCancelado = ($ag->status === 'cancelado');
                            $podeEditar = $isAdmin || (!$bloqueado && !$statusCancelado);
                            $podeCancelar = ($ag->status === 'pendente' || $ag->status === 'confirmado') && (!$bloqueado || $isAdmin);
                            ?>

                            <?php if ($podeEditar): ?>
                                <a href="/agendamentos/editar/<?= $ag->id ?>" title="Editar">
                                    <button class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></button>
                                </a>
                            <?php endif; ?>

                            <?php if ($podeCancelar): ?>
                                <a href="/agendamentos/cancelar/<?= $ag->id ?>"
                                    onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')"
                                    title="Cancelar">
                                    <button class="btn btn-warning btn-sm"><i class="bi bi-x-square"></i></button>
                                </a>
                            <?php endif; ?>

                            <?php if ($isAdmin): ?>
                                <a href="/agendamentos/excluir/<?= $ag->id ?>" title="Excluir">
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
// Paginação
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