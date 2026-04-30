<?php
// Variáveis disponíveis:
// $objAgendamento   → instância existente (update) ou null (create)
// $servicos         → todos os serviços
// $clientes         → todos os clientes (admin)
// $servicosMarcados → array de IDs já marcados (update)
// $bloqueado        → bool: edição travada por 48h
// $isAdmin          → bool
// $error            → string de erro
// $sugestao         → array|null com sugestão de agrupamento de horário
// $date, $time_start, $servicos_ids, $cliente_id → valores do POST (para repreencher o form após sugestão)

$servicosMarcados = $servicosMarcados ?? (isset($servicos_ids) ? $servicos_ids : []);
$bloqueado        = $bloqueado ?? false;
$error            = $error ?? '';
$sugestao         = $sugestao ?? null;

// Valores para pré-preencher o form (vêm do POST quando a sugestão é exibida)
$preDate      = isset($date)       ? $date                        : (isset($objAgendamento) ? $objAgendamento->date       : '');
$preTime      = isset($time_start) ? substr($time_start, 0, 5)   : (isset($objAgendamento) ? substr($objAgendamento->time_start, 0, 5) : '08:00');
$preCliente   = isset($cliente_id) ? $cliente_id                  : (isset($objAgendamento) ? $objAgendamento->cliente_id : '');

// Limites de data
$minDate = date('Y-m-d');
$maxDate = date('Y-m-t', strtotime('last day of next month'));
?>

<section>
    <div class="d-flex align-items-center gap-3 mt-3 mb-4">
        <a href="/agendamentos">
            <button class="btn btn-danger"><i class="bi bi-arrow-left-circle"></i> Voltar</button>
        </a>
        <h3 class="mb-0"><?= TITLE ?></h3>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($bloqueado): ?>
        <div class="alert alert-warning">
            <strong>Edição bloqueada.</strong> Faltam menos de 48 horas para o atendimento. Entre em contato com o salão para alterações.
        </div>
    <?php else: ?>

        <!-- Sugestão de agrupamento de horários na semana -->
        <?php if ($sugestao): ?>
            <div class="alert alert-info d-flex flex-column gap-2" role="alert">
                <div>
                    <i class="bi bi-lightbulb-fill me-2 text-warning"></i>
                    <strong>Sugestão de horário!</strong>
                </div>
                <p class="mb-1">
                    Você já tem um agendamento marcado nessa semana em
                    <strong><?= $sugestao['ag_existente_data'] ?></strong>
                    das <strong><?= $sugestao['ag_existente_inicio'] ?></strong>
                    às <strong><?= $sugestao['ag_existente_fim'] ?></strong>.
                </p>
                <p class="mb-2">
                    Que tal aproveitar e agendar este atendimento logo em seguida, em
                    <strong><?= $sugestao['date_fmt'] ?></strong>
                    às <strong><?= $sugestao['time_start'] ?></strong>
                    (término previsto às <strong><?= $sugestao['time_end'] ?></strong>)?
                    Assim você resolve tudo de uma vez!
                </p>

                <!-- Formulário oculto para aceitar a sugestão -->
                <!-- Reenvia os dados originais do POST com date/time trocados pelo sugerido -->
                <form method="post" id="form-sugestao" class="d-inline">
                    <input type="hidden" name="date" value="<?= $sugestao['date'] ?>">
                    <input type="hidden" name="time_start" value="<?= $sugestao['time_start'] ?>">
                    <input type="hidden" name="usar_sugestao" value="1">
                    <?php if ($isAdmin && $preCliente): ?>
                        <input type="hidden" name="cliente_id" value="<?= $preCliente ?>">
                    <?php endif; ?>
                    <?php foreach ($servicosMarcados as $sid): ?>
                        <input type="hidden" name="servicos[]" value="<?= $sid ?>">
                    <?php endforeach; ?>
                    <?php if (isset($objAgendamento)): ?>
                        <?php if (isset($_POST['status'])): ?>
                            <input type="hidden" name="status" value="<?= htmlspecialchars($_POST['status']) ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle me-1"></i> Usar horário sugerido
                        </button>
                        <button type="button" onclick="
                            var inp = document.createElement('input');
                            inp.type = 'hidden';
                            inp.name = 'usar_sugestao';
                            inp.value = '1';
                            document.getElementById('manter-horario').appendChild(inp);
                            document.getElementById('manter-horario').submit();
                        ">
                            <i class='bi bi-x-circle me-1'></i> Manter meu horário escolhido
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Formulário principal -->
        <!-- "manter-horario" é usado pelo botão de ignorar a sugestão -->
        <form method="post" id="manter-horario">
            <!-- <input type="hidden" name="usar_sugestao" value="">Marca como ignorada para não repetir a sugestão -->

            <?php if ($isAdmin): ?>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <label class="form-label">Cliente</label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="" disabled <?= !$preCliente ? 'selected' : '' ?>>Selecione um cliente...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c->id ?>" <?= $preCliente == $c->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c->name . ' ' . $c->surname) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Data</label>
                    <input type="date" id="date" name="date" class="form-control"
                        min="<?= $minDate ?>" max="<?= $maxDate ?>"
                        value="<?= htmlspecialchars($preDate) ?>" required>
                    <div id="date-warning" class="text-danger small mt-1" style="display:none;">
                        Não é possível agendar aos domingos.
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label">Horário de Início</label>
                    <select id="time_start" name="time_start" class="form-select" required>
                        <?php
                        $start    = new DateTime('08:00');
                        $end      = new DateTime('17:30');
                        $interval = new DateInterval('PT15M');
                        $period   = new DatePeriod($start, $interval, $end->modify('+1 minute'));

                        foreach ($period as $dt) {
                            $hora     = $dt->format('H:i');
                            $selected = ($hora === $preTime) ? 'selected' : '';
                            echo "<option value=\"$hora\" $selected>$hora</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col">
                    <label class="form-label">Serviços</label>
                    <div class="border rounded p-3">
                        <?php foreach ($servicos as $s): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input servico-check" type="checkbox"
                                    name="servicos[]" value="<?= $s->id ?>"
                                    id="s<?= $s->id ?>"
                                    data-duration="<?= $s->duration_min ?>"
                                    data-price="<?= $s->price ?>"
                                    <?= in_array($s->id, $servicosMarcados) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="s<?= $s->id ?>">
                                    <?= htmlspecialchars($s->name) ?>
                                    — <?= $s->duration_min ?>min
                                    — R$ <?= number_format($s->price, 2, ',', '.') ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($isAdmin && isset($objAgendamento)): ?>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <label class="form-label">Status do Agendamento</label>
                        <select name="status" class="form-select">
                            <option value="pendente" <?= $objAgendamento->status == 'pendente'   ? 'selected' : '' ?>>Pendente</option>
                            <option value="confirmado" <?= $objAgendamento->status == 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                            <option value="concluido" <?= $objAgendamento->status == 'concluido'  ? 'selected' : '' ?>>Concluído</option>
                            <option value="cancelado" <?= $objAgendamento->status == 'cancelado'  ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Resumo calculado em tempo real -->
            <div class="card mb-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">Resumo do Agendamento</h6>
                    <p class="mb-1">Previsão de término: <strong id="time_end_display">—</strong></p>
                    <p class="mb-0">Total: <strong id="total_price_display">R$ 0,00</strong></p>
                    <div id="time-warning" class="text-danger small mt-2" style="display:none;">
                        O atendimento ultrapassaria as 18h. Ajuste o horário ou os serviços.
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col d-grid">
                    <button id="btn-submit" class="btn btn-primary" type="submit">Confirmar Agendamento</button>
                </div>
            </div>
        </form>

    <?php endif; ?>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referências aos elementos do formulário
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time_start');
        const checks = document.querySelectorAll('.servico-check');
        const btnSubmit = document.getElementById('btn-submit');

        /**
         * Recalcula o preço total, o horário de término e valida o formulário
         * sempre que o horário de início ou os serviços selecionados mudarem.
         */
        function calcular() {
            const timeVal = timeInput.value;
            const selecionados = [...checks].filter(c => c.checked);

            // Soma a duração (em minutos) e o preço de todos os serviços marcados
            let totalMin = selecionados.reduce((s, c) => s + parseInt(c.dataset.duration), 0);
            let totalPrice = selecionados.reduce((s, c) => s + parseFloat(c.dataset.price), 0);

            // Atualiza o preço total exibido no formato R$ 0,00
            document.getElementById('total_price_display').textContent =
                'R$ ' + totalPrice.toFixed(2).replace('.', ',');

            // Se não há horário ou nenhum serviço selecionado, limpa o display e encerra
            if (!timeVal || selecionados.length === 0) {
                document.getElementById('time_end_display').textContent = '—';
                document.getElementById('time-warning').style.display = 'none';
                return;
            }

            // Converte o horário de início em minutos e soma a duração total
            const [h, m] = timeVal.split(':').map(Number);
            const endMin = h * 60 + m + totalMin;

            // Converte o resultado de volta para o formato HH:MM
            const endH = Math.floor(endMin / 60);
            const endM = endMin % 60;
            const timeEnd = String(endH).padStart(2, '0') + ':' + String(endM).padStart(2, '0');

            // Exibe o horário de término calculado
            document.getElementById('time_end_display').textContent = timeEnd;

            // Verifica se o atendimento ultrapassa o limite das 18h
            const ultrapass = endH > 18 || (endH === 18 && endM > 0);
            document.getElementById('time-warning').style.display = ultrapass ? 'block' : 'none';

            // Bloqueia o envio do formulário se ultrapassar o horário limite
            if (btnSubmit) btnSubmit.disabled = ultrapass;
        }

        /**
         * Impede a seleção de domingos no campo de data.
         * Limpa o valor e exibe um aviso caso o usuário selecione um domingo.
         */
        dateInput.addEventListener('change', function() {
            const d = new Date(this.value + 'T00:00:00');
            const warn = document.getElementById('date-warning');

            if (d.getDay() === 0) { // 0 = domingo
                warn.style.display = 'block';
                this.value = '';
                if (btnSubmit) btnSubmit.disabled = true;
            } else {
                warn.style.display = 'none';
                if (btnSubmit) btnSubmit.disabled = false;
            }
        });

        // Recalcula ao marcar/desmarcar qualquer serviço ou alterar o horário
        checks.forEach(c => c.addEventListener('change', calcular));
        timeInput.addEventListener('change', calcular);
        timeInput.addEventListener('input', calcular);

        // Executa ao carregar a página para refletir valores já preenchidos
        calcular();
    });
</script>