<?php

namespace App\Models;

use App\DB\Database;
use \PDO;

class Servico
{
    /** * Identificador único do serviço.
     * @var integer
     */
    public $id;

    /** * Categoria do serviço ('c' cabelo, 'u' unha, 'e' estética).
     * @var string
     */
    public $cat;

    /** * Nome descritivo do serviço.
     * @var string
     */
    public $name;

    /** * Tempo estimado de execução em minutos.
     * @var integer
     */
    public $duration_min;

    /** * Valor monetário do serviço.
     * @var float
     */
    public $price;

    /** * Persiste um novo serviço no banco de dados.
     * @return boolean
     */
    public function registerServico()
    {
        $objDatabase = new Database('tbServicos');
        $this->id = $objDatabase->insertData([
            'cat'          => $this->cat,
            'name'         => $this->name,
            'duration_min' => $this->duration_min,
            'price'        => $this->price
        ]);

        return true;
    }

    /** * Atualiza os dados do serviço atual.
     * @return boolean
     */
    public function updateServico()
    {
        return (new Database('tbServicos'))->updateData('id = ' . $this->id, [
            'cat'          => $this->cat,
            'name'         => $this->name,
            'duration_min' => $this->duration_min,
            'price'        => $this->price
        ]);
    }

    /** * Remove o registro do serviço do sistema.
     * @return boolean
     */
    public function deleteServico()
    {
        return (new Database('tbServicos'))->delete('id = ' . $this->id);
    }

    /** * Obtém uma lista de serviços com filtros opcionais.
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @return array Lista de objetos Servico
     */
    public static function getServicos($where = null, $order = null, $limit = null)
    {
        return (new Database('tbServicos'))->selectDB($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /** * Retorna o total de registros encontrados para uma condição.
     * @param string|null $where
     * @return integer
     */
    public static function getLenServicos($where = null)
    {
        return (new Database('tbServicos'))->selectDB($where, null, null, 'COUNT(*) as len')->fetchObject()->len;
    }

    /** * Busca um serviço específico pelo ID.
     * @param integer $id
     * @return Servico|false
     */
    public static function getServico($id)
    {
        return (new Database('tbServicos'))->selectDB('id =' . $id)->fetchObject(self::class);
    }

    /**
     * Recupera múltiplos serviços através de uma lista de IDs.
     * @param array $ids Vetor de inteiros contendo os IDs desejados.
     * @return Servico[]
     */
    public static function getServicosById(array $ids)
    {
        if (empty($ids)) return [];

        // Garante que os IDs sejam inteiros para evitar SQL Injection
        $placeholders = implode(',', array_map('intval', $ids));

        return (new Database('tbServicos'))
            ->selectDB('id IN (' . $placeholders . ')')
            ->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Calcula a soma total do tempo de execução de vários serviços.
     * Utiliza a função agregadora SUM do SQL para performance.
     * @param array $ids
     * @return integer Total em minutos.
     */
    public static function getTotalDuration(array $ids)
    {
        if (empty($ids)) return 0;

        $placeholders = implode(',', array_map('intval', $ids));

        $result = (new Database('tbServicos'))
            ->executeQuery(
                'SELECT SUM(duration_min) as total FROM tbServicos WHERE id IN (' . $placeholders . ')',
                []
            )->fetchObject();

        return $result ? (int) $result->total : 0;
    }
}
