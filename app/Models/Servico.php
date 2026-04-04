<?php

namespace App\Models;

use App\DB\Database;
use \PDO;


class Servico
{

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

    public function registerServico()
    {

        //Inserir Servico no Bnaco de dados
        $objDatabase = new Database('tbServicos');
        $this->id = $objDatabase->insertData([
            'cat'          => $this->cat,
            'name'         => $this->name,
            'duration_min' => $this->duration_min,
            'price'        => $this->price
        ]);

        // Retorno de Sucesso
        return true;
    }

    /** 
     * Atualizar Servico no banco de daodos
     */
    public function updateServico()
    {

        //Inserir Servico no Bnaco de dados
        return (new Database('tbServicos'))->updateData('id = ' . $this->id, [
            'cat'          => $this->cat,
            'name'         => $this->name,
            'duration_min' => $this->duration_min,
            'price'        => $this->price
        ]);
    }

    /** 
     * Excluir Servico
     */

    public function deleteServico()
    {
        return (new Database('tbServicos'))->delete('id = ' . $this->id);
    }

    /** 
     * Método responsável por obter os Servicos do DB
     */
    public static function getServicos($where = null, $order = null, $limit = null)
    {
        return (new Database('tbServicos'))->selectDB($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /** 
     * Método responsável por obter a quantidade de Servicos do BD
     */
    public static function getLenServicos($where = null)
    {
        return (new Database('tbServicos'))->selectDB($where, null, null, 'COUNT(*) as len')->fetchObject()->len;
    }

    /** 
     * Método responsável por buscar um vaga com base em seu ID
     */
    public static function getServico($id)
    {
        return (new Database('tbServicos'))->selectDB('id =' . $id)->fetchObject(self::class);
    }
}
