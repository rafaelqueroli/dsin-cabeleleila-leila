<?php

namespace App\DB;

use \PDO;
use PDOException;

class Database
{
/** @var string Nome da tabela */
    private $table;

    /** @var PDO Instância de conexão */
    private $conn;

    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    private function setConnection()
    {
        try {
            // Buscamos os valores que o Dotenv carregou
            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $port = $_ENV['DB_PORT'] ?? '3306';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
            
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Erro crítico de conexão: " . $e->getMessage());
        }
    }

    /**
     * Executa consultas (Queries) no banco de dados com suporte a parâmetros (binds).
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function executeQuery($query, $params = [])
    {
        try {
            $statment = $this->conn->prepare($query);
            $statment->execute($params);
            return $statment;
        } catch (PDOException $e) {
            die("Erro na query: " . $e->getMessage());
        }
    }

    /**
     * Insere dados dinamicamente no banco.
     * @param array $data [ campo => valor ]
     * @return integer ID do registro inserido.
     */
    public function insertData($data)
    {
        // Extrai os nomes das colunas e gera os placeholders (?)
        $fields = array_keys($data);
        $binds  = array_pad([], count($data), '?');

        // Montagem da instrução SQL
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        // Execução com Prepared Statement para segurança
        $this->executeQuery($query, array_values($data));

        return $this->conn->lastInsertId();
    }

    /**
     * Realiza consultas SELECT de forma simplificada.
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public function selectDB($where = null, $order = null, $limit = null, $fields = '*')
    {
        // Formata as cláusulas caso existam
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->executeQuery($query);
    }

    /**
     * Atualiza registros com base em uma condição.
     * @param string $where
     * @param array $data [ campo => valor ]
     * @return boolean
     */
    public function updateData($where, $data)
    {
        // Prepara os campos para a cláusula SET (campo=?)
        $fields = array_keys($data);
        $query  = 'UPDATE ' . $this->table . ' SET ' . implode('=?, ', $fields) . '=? WHERE ' . $where;

        $this->executeQuery($query, array_values($data));

        return true;
    }

    /**
     * Remove registros do banco de dados.
     * @param string $where
     * @return boolean
     */
    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        $this->executeQuery($query);

        return true;
    }
}
