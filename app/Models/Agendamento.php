<?php

// Definindo a localização do arquivo
namespace App\Models;

// Chamando o Banco e o PDO
use App\DB\Database;
use \PDO;

// Objeto da tabela de agendamentos (tbAgendamentos)
class Agendamento
{

    /**
     * ID do Agendamento
     *
     * @var int
     */
    public $id;

    /**
     * ID do Cliente atrelado ao agendamento
     *
     * @var int
     */
    public $cliente_id;

    /**
     * Data marcada do Agendamento (YYYY-MM-DD)
     * - Definida pelo Cliente
     * 
     * @var string
     */
    public $date;

    /**
     * Horário de Início do Agendamento
     * - Definido pelo Cliente
     *
     * @var string
     */
    public $time_start;

    /**
     * Horário (Previsto) de encerramento do Agendamento
     * - Calculado pelo sistema, no ControllerAgendamento
     * 
     * @var string
     */
    public $time_end;

    /**
     * Status do Serviço:
     * - enum ('pendente', 'confirmado', 'concluido', 'cancelado')
     *
     * @var string
     */
    public $status;

    /**
     * Data de Criação do Agendamento no Sistema
     * - Preenchido automaticamente
     *
     * @var string
     */
    public $created_at;

    /**
     * Função responsável pelo registro de um Agendamento do sistema
     * - Acessa o banco no objeto Database, especificando a tabela de Agendamentos
     * - Insere os dados na tabela por meio da função insertData() do objeto Database()
     *
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
     * Vincula um dos serviço ao agendamento, criando uma linha na tabela tbAgendamentosServicos
     * 
     * @param int $servico_id
     * @param float $price (Preço praticado no momento da reserva)
     * 
     * @return void
     */
    public function addServico($servico_id, $price)
    {
        (new Database('tbAgendamentosServicos'))->insertData([
            'agendamento_id' => $this->id,
            'servico_id'     => $servico_id,
            'price'     => $price,
        ]);
    }

    /**
     * Função que remove todos os elementos da tabela tbAgendamentosServicos relacionados ao agendamento que está sendo excluído
     * 
     * @return void
     */
    public function removeServicos()
    {
        (new Database('tbAgendamentosServicos'))->delete('agendamento_id = ' . $this->id);
    }

    /**
     * Função responsável por enviar os elementos atualizados para a linha da tabela relaciona ao id do agendamento
     * 
     * @return void
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
     * Exclui o registro do agendamento e todos os serviços da tabela tbAgendamentosServicos relacionada ao Agendamento
     * 
     * @return void
     */
    public function deleteAgendamento()
    {
        $this->removeServicos();
        return (new Database('tbAgendamentos'))->delete('id = ' . $this->id);
    }

    /**
     * Resgata a lista da tabela tbAgendamentos com ordenação padrão definida por ordem cronológica (Data menor Primeiro)
     * 
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * 
     * @return Agendamento[]
     */
    public static function getAgendamentos($where = null, $order = null, $limit = null)
    {
        return (new Database('tbAgendamentos'))
            // Define 
            ->selectDB($where, $order ?? 'date ASC, time_start ASC', $limit)
            ->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Conta o total de agendamentos para paginação.
     * 
     * @param string|null $where
     * 
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
     * 
     * @param int $id
     * 
     * @return Agendamento|false
     */
    public static function getAgendamento($id)
    {
        return (new Database('tbAgendamentos'))
            ->selectDB('id = ' . $id)
            ->fetchObject(self::class);
    }

    /**
     * Mostra os serviços vinculados ao agendamento e os seus preços
     * 
     * @param int $agendamento_id
     * 
     * @return array
     */
    public static function getServicosDoAgendamento($agendamento_id)
    {
        // Criação da Querry
        return (new Database('tbAgendamentosServicos'))
            ->executeQuery(
                'SELECT s.*, aes.price as preco_cobrado
                 FROM tbAgendamentosServicos aes
                 JOIN tbServicos s ON s.id = aes.servico_id
                 WHERE aes.agendamento_id = ?',
                [$agendamento_id]
            )->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Calcula o valor total do agendamento somando os serviços vinculados.
     * 
     * @param int $agendamento_id
     * 
     * @return float
     */
    public static function getTotalPrice($agendamento_id)
    {
        $result = (new Database('tbAgendamentosServicos'))
            ->executeQuery(
                'SELECT SUM(price) as total FROM tbAgendamentosServicos WHERE agendamento_id = ?',
                [$agendamento_id]
            )->fetchObject();

        return $result ? (float) $result->total : 0.0;
    }

    /**
     * Verifica se há conflito de horário para um agendamento.
     *
     * Busca agendamentos ativos (não cancelados) na mesma data que se
     * oponham ao intervalo informado, usando a lógica de interseção
     * de intervalos: time_start < $time_end AND time_end > $time_start.
     *
     * @param string   $date       Data do agendamento (Y-m-d).
     * @param string   $time_start Horário de início (H:i:s).
     * @param string   $time_end   Horário de término (H:i:s).
     * @param int|null $exclude_id ID do agendamento a ignorar na verificação (útil em edições).
     *
     * @return bool True se houver conflito, False se o horário estiver disponível.
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
     * Retorna os agendamentos ativos de um cliente na semana da data informada.
     *
     * Calcula automaticamente o intervalo de segunda a domingo com base na
     * data fornecida, ignorando agendamentos cancelados ou concluídos.
     *
     * @param int      $cliente_id ID do cliente.
     * @param string   $date       Data de referência para calcular a semana (Y-m-d).
     * @param int|null $exclude_id ID do agendamento a ignorar na busca (útil em edições).
     *
     * @return array Lista de agendamentos ativos da semana.
     */
    public static function getAgendamentosDaSemana(int $cliente_id, string $date, ?int $exclude_id = null)
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
