<?php

namespace App\Models;

Class Servico {

    // Definição das Variáveis
    /** 
     * Identificador do Servico;
     * @var integer;
    */
    public $id;

    /** 
     * Categoria do Servico;
     * @var string('1' | '2' | '3');
    */
    public $cat;

    /** 
     * Nome do Servico;
     * @var string;
    */
    public $name;
    
    /** 
     * Duração mínima do Serviço;
     * @var integer;
    */
    public $duration_min;

    /** 
     * Preço do Serviço;
     * @var float (10, 2);
    */
    public $price;
}

?>
