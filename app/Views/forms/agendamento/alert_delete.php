<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="/agendamentos">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Voltar</button>
        </a>
        <h3 class="mb-0">Cancelar Agendamento</h3>
    </div>

    <div class="alert alert-warning">
        <strong>Atenção!</strong> Tem certeza que deseja excluir o agendamento abaixo?
    </div>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Data:</strong> <?= date('d/m/Y', strtotime($objAgendamento->date)) ?></li>
        <li class="list-group-item"><strong>Horário:</strong> <?= substr($objAgendamento->time_start, 0, 5) ?> – <?= substr($objAgendamento->time_end, 0, 5) ?></li>
        <li class="list-group-item"><strong>Status:</strong> <?= ucfirst($objAgendamento->status) ?></li>
    </ul>

    <form method="post" class="d-flex gap-2">
        <button type="submit" name="delete" value="1" class="btn btn-danger">
            <i class="bi bi-trash-fill"></i> Confirmar Exclusão
        </button>
        <a href="/agendamentos" class="btn btn-secondary">Cancelar</a>
    </form>
</section>