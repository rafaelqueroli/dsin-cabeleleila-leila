<?php

// Definição do namespace pro autoload
namespace App\Models;

// Declaração do Objeto Database e do PDO
use App\DB\Database;
use \PDO;

class Usuario
{
    /** 
     * Identificador do Usuário;
     * @var integer
     */
    public $id;

    /** 
     * Nome do Usuário;
     * @var string
     */
    public $name;

    /** 
     * Nome do Usuário;
     * @var string
     */
    public $surname;

    /** 
     * Email do Usuário;
     * @var string
     */
    public $email;

    /** 
     * Número de Telefone do Usuário;
     * @var string
     */
    public $phone_n;

    public string $pass;

    /** 
     * Função do Usuário (Cliente, Funcionário e Admin);
     * @var string('c','a')
     */
    public $role;

    /** 
     * Date e Hora de Criação do Usuário;
     * @var string('YYYY-MM-DD HH:MM:SS')
     */
    public $create_at;

    /** 
     * Método responsável por cadastrar um novo usuário
     */
    public function registerUsuario()
    {

        //Inserir Usuário no Bnaco de dados
        $objDatabase = new Database('tbUsuarios');
        $this->id    = $objDatabase->insertData([
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
     * Atualizar Usuário no banco de daodos
     */
    public function updateUsuario()
    {

        //Inserir Usuário no Bnaco de dados
        return (new Database('tbUsuarios'))->updateData('id = ' . $this->id, [
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'phone_n' => $this->phone_n,
            'pass'    => $this->pass,
            'role'    => $this->role
        ]);
    }

    /** 
     * Excluir usuário
     */

    public function deleteUsuario()
    {
        return (new Database('tbUsuarios'))->delete('id = ' . $this->id);
    }

    /** 
     * Método responsável por obter os Usuários do DB
     */
    public static function getUsuarios($where = null, $order = null, $limit = null)
    {
        return (new Database('tbUsuarios'))->selectDB($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /** 
     * Método responsável por obter a quantidade de Usuários do BD
     */
    public static function getLenUsuarios($where = null)
    {
        return (new Database('tbUsuarios'))->selectDB($where, null, null, 'COUNT(*) as len')->fetchObject()->len;
    }
    /** 
     * Método responsável por buscar um vaga com base em seu ID
     */
    public static function getUsuario($id)
    {
        return (new Database('tbUsuarios'))->selectDB('id =' . $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instância de usuário com base em seu e-mail
     * @param string $email
     * 
     * @return Usuario|false
     */
    public static function getUsuariobyEmail($email) {
        return (new Database('tbUsuarios'))->selectDB('email = "' .$email. '"')->fetchObject(self::class);
    }
}

class Cliente extends Usuario {}

class Admin extends Usuario {}
