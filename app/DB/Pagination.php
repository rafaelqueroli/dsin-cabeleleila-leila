<?php

namespace App\DB;

class Pagination
{
    /** * @var integer Número máximo de registros por página.
     */
    private $limit;

    /** * @var integer Quantidade total de registros encontrados no banco de dados.
     */
    private $res;

    /** * @var integer Total de páginas calculadas.
     */
    private $pages;

    /** * @var integer Índice da página atual.
     */
    private $current_page;

    /**
     * Inicializa a configuração da paginação.
     * @param integer $res Total de resultados da consulta.
     * @param integer $current_page Página solicitada pelo usuário.
     * @param integer $limit Quantidade de itens por página.
     */
    public function __construct($res, $current_page = 1, $limit = 5)
    {
        $this->res   = $res;
        $this->limit = $limit;

        // Garante que a página atual seja um número válido e positivo
        $this->current_page = (is_numeric($current_page) && $current_page > 0) ? (int)$current_page : 1;

        $this->calculate();
    }

    /**
     * Executa o processamento matemático para determinar o total de páginas
     * e validar se a página atual não excede o limite existente.
     * @return void
     */
    private function calculate()
    {
        // Define o total de páginas utilizando o arredondamento para cima (ceil)
        $this->pages = $this->res > 0 ? ceil($this->res / $this->limit) : 1;

        // Ajusta a página atual caso ela seja maior que o total de páginas disponíveis
        if ($this->current_page > $this->pages) {
            $this->current_page = $this->pages;
        }
    }

    /**
     * Gera a string de limite para ser utilizada na query SQL (cláusula LIMIT).
     * @return string Exemplo: "0,5" ou "10,5"
     */
    public function getLimit()
    {
        // O offset determina a partir de qual registro o banco deve começar a leitura
        $offset = ($this->limit * ($this->current_page - 1));
        return $offset . ',' . $this->limit;
    }

    /**
     * Gera uma estrutura de array com as informações de navegação.
     * @return array Lista de páginas com indicadores de página atual.
     */
    public function getPages()
    {
        // Retorna vazio se houver apenas uma página (navegação desnecessária)
        if ($this->pages <= 1) return [];

        $paginas = [];
        for ($i = 1; $i <= $this->pages; $i++) {
            $paginas[] = [
                'p'       => $i,
                'current' => $i == $this->current_page
            ];
        }
        return $paginas;
    }
}
