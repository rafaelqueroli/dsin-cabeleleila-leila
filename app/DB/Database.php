<?php

namespace App\DB;

use \PDO;
use PDOException;

class Database
{

    /**
     * Definição das constantes relativas às credenciais do Banco de Dados
     */
    const HOST = 'localhost';
    const DB   = 'bd-cabeleleila-leila';
    const USER = 'root';
    const PASS = '';

    /**
     * Nome da tabela que está sendo acessada
     * @var string
     */
    private $table;

    /**
     * Instancia de conexão com o banco de dados
     * @var PDO
     */
    private $conn;


    /** 
     * Define a taebla e a instância de conexão
     */

    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /** 
     * Método responsável por estabelecer a conexão com o banco
     */

    private function setConnection()
    {
        try {
            $this->conn = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB, self::USER, self::PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /** 
     * Método responsável por executar Queries dentro do DB
     */
    public function executeQuery($query, $params = [])
    {
        try {
            $statment = $this->conn->prepare($query);
            $statment->execute($params);
            return $statment;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /** 
     * Método responsável por inserir Dados no Banco
     */
    public function insertData($data)
    {
        // Dados da Query
        $fields = array_keys($data);
        $binds  = array_pad([], count($data), '?');

        // Montagem da Query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        // Insert de dados
        $this->executeQuery($query, array_values($data));

        return $this->conn->lastInsertId();
    }

    /** 
     * Método responsável por executar uma consulta no banco
     */
    public function selectDB($where = null, $order = null, $limit = null, $fields = '*')
    {
        // Dados da Query
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . 'FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->executeQuery($query);
    }
}
