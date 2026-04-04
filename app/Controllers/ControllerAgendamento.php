<?php

namespace App\Controllers;

use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\Usuario;
use App\Session\Login;

class ControllerAgendamento
{
    /**
     * Calcula o horário de término com base na duração total dos serviços.
     */
    private function calcularTimeEnd(string $time_start, int $total_minutos): string
    {
        $inicio = strtotime($time_start);
        return date('H:i:s', $inicio + ($total_minutos * 60));
    }

    /**
     * Valida as regras de negócio: datas passadas, domingos, limites de meses e conflitos.
     */
    private function validar(string $date, string $time_start, array $servicos_ids, ?int $exclude_id = null): string
    {
        $hoje      = new \DateTime('today');
        $agora     = new \DateTime();
        $dataAgend = new \DateTime($date);

        if ($dataAgend < $hoje) {
            return 'Não é possível agendar em datas passadas.';
        }

        if ($dataAgend->format('N') == 7) {
            return 'Não é possível agendar aos domingos.';
        }

        $fimProximoMes = new \DateTime('last day of next month');
        $fimProximoMes->setTime(23, 59, 59);
        $inicioDaqui   = new \DateTime('first day of this month');
        if ($dataAgend < $inicioDaqui || $dataAgend > $fimProximoMes) {
            return 'O agendamento só pode ser feito no mês atual ou no próximo.';
        }

        if ($dataAgend == $hoje) {
            $horarioPedido = new \DateTime($date . ' ' . $time_start);
            if ($horarioPedido <= $agora) {
                return 'Não é possível agendar em um horário que já passou.';
            }
        }

        $h = (int) explode(':', $time_start)[0];
        if ($h < 8 || $h >= 18) {
            return 'O horário de início deve ser entre 08:00 e 18:00.';
        }

        if (empty($servicos_ids)) {
            return 'Selecione ao menos um serviço.';
        }

        $total_min = Servico::getTotalDuration($servicos_ids);
        $time_end  = $this->calcularTimeEnd($time_start, $total_min);

        if ($time_end > '18:00:00') {
            return 'A soma dos serviços ultrapassa o horário limite (18h).';
        }

        if (Agendamento::hasConflict($date, $time_start, $time_end, $exclude_id)) {
            return 'Já existe um agendamento neste horário. Escolha outro horário.';
        }

        return '';
    }

    /**
     * Verifica se existe agendamento do cliente na mesma semana e se o slot
     * logo após esse agendamento comporta os novos serviços.
     *
     * Retorna um array com a sugestão ou null se não houver oportunidade.
     * A sugestão aponta sempre para o agendamento mais cedo da semana
     * cujo término tenha espaço para o novo atendimento.
     *
     * @param int    $cliente_id
     * @param string $date        Data que o usuário escolheu (referência de semana).
     * @param int    $total_min   Duração total dos serviços selecionados em minutos.
     * @return array|null
     */
    private function calcularSugestao($cliente_id, $date, $total_min, $exclude_id)
    {
        $agendamentosSemana = Agendamento::getAgendamentosDaSemana($cliente_id, $date, $exclude_id);

        if (empty($agendamentosSemana)) {
            return null;
        }

        foreach ($agendamentosSemana as $ag) {
            $dataSugerida      = $ag->date;
            $inicioSugerido    = $ag->time_end; // Novo começa onde o existente termina
            $fimSugerido       = $this->calcularTimeEnd($inicioSugerido, $total_min);

            // Verifica se o novo atendimento cabe antes das 18h
            if ($fimSugerido > '18:00:00') {
                continue;
            }

            // Verifica se não há conflito com outro agendamento nesse slot
            if (Agendamento::hasConflict($dataSugerida, $inicioSugerido, $fimSugerido)) {
                continue;
            }

            // Retorna a sugestão com dados formatados para exibição na view
            return [
                'date'               => $dataSugerida,
                'date_fmt'           => (new \DateTime($dataSugerida))->format('d/m/Y'),
                'time_start'         => substr($inicioSugerido, 0, 5),
                'time_end'           => substr($fimSugerido, 0, 5),
                'ag_existente_data'  => (new \DateTime($ag->date))->format('d/m/Y'),
                'ag_existente_inicio' => substr($ag->time_start, 0, 5),
                'ag_existente_fim'   => substr($ag->time_end, 0, 5),
            ];
        }

        return null;
    }

    /**
     * Processa a criação de um novo agendamento.
     */
    public function create(): void
    {
        define('TITLE', 'Novo Agendamento');

        $userLogado = Login::getUsuarioLoged();
        $isAdmin    = $userLogado['role'] === 'a';
        $servicos   = Servico::getServicos();
        $clientes   = $isAdmin ? Usuario::getUsuarios('role = "c"') : [];
        $error      = '';
        $sugestao   = null; // Sugestão de agrupamento de horários na semana

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['time_start'], $_POST['servicos'])) {
            $date         = $_POST['date'];
            $time_start   = $_POST['time_start'] . ':00';
            $servicos_ids = array_map('intval', $_POST['servicos']);
            $cliente_id   = $isAdmin ? (int) $_POST['cliente_id'] : (int) $userLogado['id'];

            // Verifica se o usuário aceitou a sugestão (clicou em "Usar horário sugerido")
            $usarSugestao = isset($_POST['usar_sugestao']) && $_POST['usar_sugestao'] === '1';

            $error = $this->validar($date, $time_start, $servicos_ids);

            if (!$error) {
                $total_min = Servico::getTotalDuration($servicos_ids);

                // Calcula sugestão e exibe ao usuário se ainda não aceitou
                // e se a data/horário escolhido não é o sugerido
                if (!$usarSugestao) {
                    $sugestao = $this->calcularSugestao($cliente_id, $date, $total_min, null);

                    // Se houver sugestão e o horário pedido for diferente do sugerido, interrompe e mostra
                    if (
                        $sugestao !== null
                        && !($sugestao['date'] === $date && $sugestao['time_start'] === substr($time_start, 0, 5))
                    ) {
                        // Repassa os dados do POST para pré-preencher o form
                        $this->renderView('forms/agendamento/form.php', compact(
                            'servicos',
                            'clientes',
                            'isAdmin',
                            'error',
                            'sugestao',
                            'date',
                            'time_start',
                            'servicos_ids',
                            'cliente_id'
                        ));
                        return;
                    }
                }

                $time_end = $this->calcularTimeEnd($time_start, $total_min);

                $obj             = new Agendamento();
                $obj->cliente_id = $cliente_id;
                $obj->date       = $date;
                $obj->time_start = $time_start;
                $obj->time_end   = $time_end;
                $obj->status     = 'pendente';
                $obj->registerAgendamento();

                foreach (Servico::getServicosById($servicos_ids) as $s) {
                    $obj->addServico($s->id, $s->price);
                }

                header('location: /agendamentos?status=success');
                exit;
            }
        }

        $this->renderView('forms/agendamento/form.php', compact('servicos', 'clientes', 'isAdmin', 'error', 'sugestao'));
    }

    /**
     * Atualiza um agendamento existente.
     */
    public function update()
    {
        define('TITLE', 'Editar Agendamento');

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /agendamentos?status=error');
            exit;
        }

        $objAgendamento = Agendamento::getAgendamento($_GET['id']);
        if (!($objAgendamento instanceof Agendamento)) {
            header('location: /agendamentos?status=error');
            exit;
        }

        $userLogado = Login::getUsuarioLoged();
        $isAdmin    = $userLogado['role'] === 'a';

        if (!$isAdmin && $objAgendamento->cliente_id != $userLogado['id']) {
            header('location: /agendamentos?status=error');
            exit;
        }

        $dataHoraAgend = new \DateTime($objAgendamento->date . ' ' . $objAgendamento->time_start);
        $agora         = new \DateTime();
        $diff          = $agora->diff($dataHoraAgend);
        $horasRestando = ($diff->days * 24) + $diff->h;
        $bloqueado     = !$isAdmin && ($dataHoraAgend <= $agora || $horasRestando < 48);

        $servicos         = Servico::getServicos();
        $clientes         = $isAdmin ? Usuario::getUsuarios('role = "c"') : [];
        $servicosMarcados = array_column(Agendamento::getServicosDoAgendamento($objAgendamento->id), 'id');
        $error            = '';
        $sugestao         = null;

        if (!$bloqueado && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['time_start'], $_POST['servicos'])) {
            $date         = $_POST['date'];
            $time_start   = $_POST['time_start'] . ':00';
            $servicos_ids = array_map('intval', $_POST['servicos']);
            $cliente_id   = $isAdmin ? (int) $_POST['cliente_id'] : (int) $userLogado['id'];
            $usarSugestao = isset($_POST['usar_sugestao']) && $_POST['usar_sugestao'] === '1';

            $error = $this->validar($date, $time_start, $servicos_ids, $objAgendamento->id);

            if (!$error) {
                $total_min = Servico::getTotalDuration($servicos_ids);

                if (!$usarSugestao) {
                    $sugestao = $this->calcularSugestao($cliente_id, $date, $total_min, $objAgendamento->id);

                    if (
                        $sugestao !== null
                        && !($sugestao['date'] === $date && $sugestao['time_start'] === substr($time_start, 0, 5))
                    ) {
                        $this->renderView('forms/agendamento/form.php', array_merge(
                            compact('objAgendamento', 'servicos', 'clientes', 'servicosMarcados', 'isAdmin', 'bloqueado', 'error', 'sugestao'),
                            compact('date', 'time_start', 'servicos_ids', 'cliente_id')
                        ));
                        return;
                    }
                }

                $time_end = $this->calcularTimeEnd($time_start, $total_min);

                $objAgendamento->cliente_id = $cliente_id;
                $objAgendamento->date       = $date;
                $objAgendamento->time_start = $time_start;
                $objAgendamento->time_end   = $time_end;

                if ($isAdmin && isset($_POST['status'])) {
                    $objAgendamento->status = $_POST['status'];
                }

                $objAgendamento->updateAgendamento();

                $objAgendamento->removeServicos();
                foreach (Servico::getServicosById($servicos_ids) as $s) {
                    $objAgendamento->addServico($s->id, $s->price);
                }

                header('location: /agendamentos?status=success');
                exit;
            }
        }

        $this->renderView('forms/agendamento/form.php', [
            'objAgendamento'   => $objAgendamento,
            'servicos'         => $servicos,
            'clientes'         => $clientes,
            'servicosMarcados' => $servicosMarcados,
            'isAdmin'          => $isAdmin,
            'bloqueado'        => $bloqueado,
            'error'            => $error,
            'sugestao'         => $sugestao,
        ]);
    }

    /**
     * Exclui permanentemente um agendamento (Apenas Administrativo).
     */
    public function delete(): void
    {
        $userLogado = Login::getUsuarioLoged();

        if ($userLogado['role'] !== 'a') {
            header('location: /agendamentos?status=error');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /agendamentos?status=error');
            exit;
        }

        $objAgendamento = Agendamento::getAgendamento($_GET['id']);
        if (!($objAgendamento instanceof Agendamento)) {
            header('location: /agendamentos?status=error');
            exit;
        }

        if (isset($_POST['delete'])) {
            $objAgendamento->deleteAgendamento();
            header('location: /agendamentos?status=success');
            exit;
        }

        $this->renderView('forms/agendamento/alert_delete.php', ['objAgendamento' => $objAgendamento]);
    }

    /**
     * Altera o status do agendamento para cancelado.
     */
    public function cancel(): void
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: /agendamentos?status=error');
            exit;
        }

        $objAgendamento = Agendamento::getAgendamento($_GET['id']);
        $userLogado     = Login::getUsuarioLoged();
        $isAdmin        = $userLogado['role'] === 'a';

        if (!$objAgendamento instanceof Agendamento) {
            header('location: /agendamentos?status=error');
            exit;
        }

        if (!$isAdmin && $objAgendamento->cliente_id != $userLogado['id']) {
            header('location: /agendamentos?status=error');
            exit;
        }

        if (in_array($objAgendamento->status, ['pendente', 'confirmado'])) {
            $objAgendamento->status = 'cancelado';
            $objAgendamento->updateAgendamento();
            header('location: /agendamentos?status=success');
        } else {
            header('location: /agendamentos?status=error');
        }
        exit;
    }

    /**
     * Renderiza as views com o padrão de layout do sistema.
     */
    private function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../Views/includes/header.php';
        include __DIR__ . '/../Views/includes/navbar.php';
        include __DIR__ . "/../Views/{$viewPath}";
        include __DIR__ . '/../Views/includes/footer.php';
    }
}
