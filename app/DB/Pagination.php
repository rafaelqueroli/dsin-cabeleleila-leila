<?php

namespace App\DB;

class Pagination
{
    /**
     * Número máximos de registros por página
     * @var integer
     */
    private $limit;

    /**
     * Quantidade total de resultados do banco
     * @var integer
     */
    private $res;

    /**
     * Quantidade de Páginas da Listagem
     * @var integer
     */
    private $pages;

    /**
     * Página Atual
     * @var integer
     */
    private $current_page;

    /**
     * Construtor da classe
     * @param int $res
     * @param int $current_page
     * @param int $limit
     */
    public function __construct($res, $current_page = 1, $limit = 5)
    {
        $this->res   = $res;
        $this->limit = $limit;
        $this->current_page = (is_numeric($current_page) && $current_page > 0) ? $current_page : 1;
        $this->calculate();
    }

    /**
     * Método Responsável pelo Cálculo da Paginação
     */
    private function calculate()
    {
        // Cálculo do Total de Páginas
        $this->pages = $this->res > 0 ? ceil($this->res / $this->limit) : 1;

        // Verificação da possibilidade da página atual
        $this->current_page = $this->current_page <= $this->pages ? $this->current_page : $this->pages;
    }

    /**
     * Método responsável por retornar a clausula limit do Mysql
     * @return str
     */
    public function getLimit()
    {
        $offset = ($this->limit * ($this->current_page - 1));
        return $offset . ',' . $this->limit;
    }

    /**
     * Método responsável por retornar as opções de páginas disponíveis
     * @return array
     */
    public function getPages()
    {
        // Não retorna páginas
        if ($this->pages == 1) return [];

        // Páginas
        $paginas =[];
        for ($i  = 1; $i <= $this->pages; $i++){
            $paginas[] = [
                'p' => $i,
                'current' => $i == $this->current_page
            ];
        }
        return $paginas;
    }
}
