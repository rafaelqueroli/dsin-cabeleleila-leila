<?php

namespace App\Models;

use App\DB\Database;
use \PDO;

class Agendamento
{
    /** @var integer Identificador único do agendamento. */
    public $id;

    /** @var integer ID do cliente associado (FK). */
    public $cliente_id;

    /** @var string Data do agendamento (YYYY-MM-DD). */
    public $date;

    /** @var string Horário de início (HH:MM:SS). */
    public $time_start;

    /** @var string Horário previsto de término (HH:MM:SS). */
    public $time_end;

    /** @var string Status (pendente, confirmado, concluido, cancelado). */
    public $status;

    /** @var string Timestamp de criação. */
    public $created_at;

    /**
     * Persiste o agendamento no banco de dados.
     * @return void
     */
    public function registerAgendamento()
    {
        $db = new Database('tbAgendamentos');
        $this->id = $db->insertData([
            'cliente_id' => $this->cliente_id,
            'date'       => $this->date,
            'time_start' => $this->time_start,
            'time_end'   => $this->time_end,
            'status'     => $this->status ?? 'pendente',
        ]);
    }

    /**
     * Vincula um serviço ao agendamento (Tabela Pivot).
     * @param int $servico_id
     * @param float $price Preço praticado no momento da reserva.
     * @return void
     */
    public function addServico($servico_id, $price)
    {
        (new Database('tbAgendamentosServicos'))->insertData([
            'agendamento_id' => $this->id,
            'servico_id'     => $servico_id,
            'finalprice'     => $price,
        ]);
    }

    /**
     * Remove todos os vínculos de serviços deste agendamento.
     * @return void
     */
    public function removeServicos()
    {
        (new Database('tbAgendamentosServicos'))->delete('agendamento_id = ' . $this->id);
    }

    /**
     * Atualiza os dados principais do agendamento.
     * @return bool
     */
    public function updateAgendamento()
    {
        return (new Database('tbAgendamentos'))->updateData('id = ' . $this->id, [
            'cliente_id' => $this->cliente_id,
            'date'       => $this->date,
            'time_start' => $this->time_start,
            'time_end'   => $this->time_end,
            'status'     => $this->status,
        ]);
    }

    /**
     * Exclui o agendamento e limpa suas dependências na tabela pivot.
     * @return bool
     */
    public function deleteAgendamento()
    {
        $this->removeServicos();
        return (new Database('tbAgendamentos'))->delete('id = ' . $this->id);
    }

    /**
     * Recupera listagem de agendamentos com ordenação cronológica padrão.
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @return Agendamento[]
     */
    public static function getAgendamentos($where = null, $order = null, $limit = null)
    {
        return (new Database('tbAgendamentos'))
            ->selectDB($where, $order ?? 'date ASC, time_start ASC', $limit)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Conta o total de agendamentos para paginação.
     * @param string|null $where
     * @return int
     */
    public static function getLenAgendamentos($where = null)
    {
        return (new Database('tbAgendamentos'))
            ->selectDB($where, null, null, 'COUNT(*) as len')
            ->fetchObject()->len;
    }

    /**
     * Busca um agendamento por ID.
     * @param int $id
     * @return Agendamento|false
     */
    public static function getAgendamento($id)
    {
        return (new Database('tbAgendamentos'))
            ->selectDB('id = ' . $id)
            ->fetchObject(self::class);
    }

    /**
     * Obtém detalhes dos serviços vinculados via JOIN.
     * @param int $agendamento_id
     * @return array Objetos contendo dados do serviço e preço histórico.
     */
    public static function getServicosDoAgendamento($agendamento_id)
    {
        return (new Database('tbAgendamentosServicos'))
            ->executeQuery(
                'SELECT s.*, aes.finalprice as preco_cobrado
                 FROM tbAgendamentosServicos aes
                 JOIN tbServicos s ON s.id = aes.servico_id
                 WHERE aes.agendamento_id = ?',
                [$agendamento_id]
            )->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Calcula o valor total do agendamento somando os serviços vinculados.
     * @param int $agendamento_id
     * @return float
     */
    public static function getTotalPrice($agendamento_id)
    {
        $result = (new Database('tbAgendamentosServicos'))
            ->executeQuery(
                'SELECT SUM(finalprice) as total FROM tbAgendamentosServicos WHERE agendamento_id = ?',
                [$agendamento_id]
            )->fetchObject();

        return $result ? (float) $result->total : 0.0;
    }

    /**
     * Valida a disponibilidade de horário.
     * Verifica se existe algum agendamento ativo que se sobreponha ao período solicitado.
     * @param string $date
     * @param string $time_start
     * @param string $time_end
     * @param int|null $exclude_id ID a ser ignorado (útil em edições).
     * @return bool True se houver conflito, False se estiver livre.
     */
    public static function hasConflict($date, $time_start, $time_end, $exclude_id = null)
    {
        $where = 'date = "' . $date . '"
            AND status != "cancelado"
            AND time_start < "' . $time_end . '"
            AND time_end > "' . $time_start . '"';

        if ($exclude_id) {
            $where .= ' AND id != ' . $exclude_id;
        }

        return (new Database('tbAgendamentos'))
            ->selectDB($where, null, null, 'COUNT(*) as len')
            ->fetchObject()->len > 0;
    }

    /**
     * Retorna todos os agendamentos ativos de um cliente na mesma semana de uma data de referência.
     * Ordenados por data e horário de início (do mais cedo para o mais tarde).
     * Usado para verificar se existe espaço para agrupar atendimentos na mesma semana.
     *
     * @param int      $cliente_id  ID do cliente.
     * @param string   $date        Data de referência.
     * @param int|null $exclude_id  ID a ser ignorado (para edições).
     */
    public static function getAgendamentosDaSemana(int $cliente_id, string $date, ?int $exclude_id = null): array
    {
        $dt = new \DateTime($date);
        $diaSemana = (int) $dt->format('N');
        $segunda   = (clone $dt)->modify('-' . ($diaSemana - 1) . ' days')->format('Y-m-d');
        $domingo   = (clone $dt)->modify('+' . (7 - $diaSemana) . ' days')->format('Y-m-d');

        $where = 'cliente_id = ' . $cliente_id . '
            AND date BETWEEN "' . $segunda . '" AND "' . $domingo . '"
            AND status NOT IN ("cancelado", "concluido")';


        if ($exclude_id) {
            $where .= ' AND id != ' . $exclude_id;
        }

        return self::getAgendamentos($where, 'date ASC, time_start ASC');
    }
}
