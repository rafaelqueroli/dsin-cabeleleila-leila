<?php

namespace App\Models;

Class Agendamento {

    // Definição das Variáveis
    /** 
     * Identificador do Agendamento;
     * @var integer;
    */
    public $id;

    /** 
     * Identificador do Usuário no Agendamento;
     * @var integer;
    */
    public $cliente_id;

    /** 
     * Identificador do Funcionário no Agendamento;
     * @var integer;
    */
    public $funcionario_id;

    /** 
     * Data do Agendamento;
     * @var string('YYYY-MM-DD');
    */
    public $date;

    /** 
     * Hora de Início do Agendamento;
     * @var string('HH:MM');
    */
    public $time_start;

    /** 
     * Hora de Final do Agendamento;
     * @var string('HH:MM');
    */
    public $time_end;

    /** 
     * Status do Agendamento;
     * @var string('pendente', 'confirmado', 'cloncluido', 'cancelado');
    */
    public $status;
    
    /** 
     * Data e Hora de Criação do Serviço;
     * @var string('YYYY-MM-DD HH:MM:SS');
    */
    public $created_at;
}

?>
