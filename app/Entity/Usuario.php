<?php

namespace App\Entity;

use App\DB\Database;
use \PDO;

class Usuario
{

    /** 
     * Identificador do Usuário;
     * @var integer;
     */
    public $id;

    /** 
     * Nome do Usuário;
     * @var string;
     */
    public $name;

    /** 
     * Nome do Usuário;
     * @var string;
     */
    public $surname;

    /** 
     * Email do Usuário;
     * @var string;
     */
    public $email;

    /** 
     * Número de Telefone do Usuário;
     * @var string;
     */
    public $phone_n;

    /** 
     * Senha do Usuário;
     * @var string;
     */
    public $pass;

    /** 
     * Função do Usuário (Cliente, Funcionário e Admin);
     * @var string('c','f','a');
     */
    public $role;

    /** 
     * Date e Hora de Criação do Usuário;
     * @var string('YYYY-MM-DD HH:MM:SS');
     */
    public $create_at;

    /** 
     * Método responsável por cadastrar um novo usuário
     */
    public function registerUsuario()
    {

        //Inserir Usuário no Bnaco de dados
        $objDatabase = new Database('tbUsuarios');
        $this->id = $objDatabase->insertData([
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'phone_n' => $this->phone_n,
            'pass'    => $this->pass,
            'role'    => $this->role
        ]);

        // Retorno de Sucesso
        return true;
    }

    /** 
     * Método responsável por obter os Usuários do DB
     */
    public static function getUsuarios($where = null, $order = null, $limit = null)
    {
        return (new Database('tbUsuarios'))->selectDB($where,$order,$limit) -> fetchAll(PDO::FETCH_CLASS,self::class);
    }
}

class Cliente extends Usuario {}

class Funcionario extends Usuario
{
    /** 
     * Tipo de serviço do Funcionario;
     * @var string('c','u','e');
     */
    public $cat;
}

class Admin extends Usuario {}
