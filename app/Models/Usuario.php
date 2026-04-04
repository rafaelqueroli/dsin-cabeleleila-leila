<?php

namespace App\Models;

use App\DB\Database;
use \PDO;

class Usuario
{
    /** * Identificador único do usuário no banco de dados.
     * @var integer
     */
    public $id;

    /** * Primeiro nome do usuário.
     * @var string
     */
    public $name;

    /** * Sobrenome do usuário.
     * @var string
     */
    public $surname;

    /** * Endereço de e-mail (usado para autenticação).
     * @var string
     */
    public $email;

    /** * Número de contato telefônico.
     * @var string
     */
    public $phone_n;

    /** * Hash da senha do usuário.
     * @var string
     */
    public $pass;

    /** * Nível de acesso/permissão ('c' para cliente, 'a' para admin).
     * @var string
     */
    public $role;

    /** * Timestamp de registro do usuário.
     * @var string Formatado como 'YYYY-MM-DD HH:MM:SS'
     */
    public $create_at;

    /** * Registra uma nova instância de usuário no banco de dados.
     * @return boolean Sucesso na operação.
     */
    public function registerUsuario()
    {
        $objDatabase = new Database('tbUsuarios');
        $this->id    = $objDatabase->insertData([
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'phone_n' => $this->phone_n,
            'pass'    => $this->pass,
            'role'    => $this->role
        ]);

        return true;
    }

    /** * Atualiza os dados do usuário atual com base no ID.
     * @return boolean Sucesso na operação.
     */
    public function updateUsuario()
    {
        return (new Database('tbUsuarios'))->updateData('id = ' . $this->id, [
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'phone_n' => $this->phone_n,
            'pass'    => $this->pass,
            'role'    => $this->role
        ]);
    }

    /** * Remove o registro do usuário atual do banco de dados.
     * @return boolean Sucesso na operação.
     */
    public function deleteUsuario()
    {
        return (new Database('tbUsuarios'))->delete('id = ' . $this->id);
    }

    /** * Recupera uma coleção de usuários.
     * @param string|null $where Condições SQL.
     * @param string|null $order Ordenação SQL.
     * @param string|null $limit Limitação SQL.
     * @return array Lista de objetos da classe Usuario.
     */
    public static function getUsuarios($where = null, $order = null, $limit = null)
    {
        return (new Database('tbUsuarios'))->selectDB($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /** * Obtém a contagem total de usuários com base em um filtro.
     * @param string|null $where Condições SQL.
     * @return integer Quantidade de registros encontrados.
     */
    public static function getLenUsuarios($where = null)
    {
        return (new Database('tbUsuarios'))->selectDB($where, null, null, 'COUNT(*) as len')->fetchObject()->len;
    }

    /** * Localiza um usuário específico através de seu ID único.
     * @param integer $id
     * @return Usuario|false Instância do usuário ou falso se não encontrado.
     */
    public static function getUsuario($id)
    {
        return (new Database('tbUsuarios'))->selectDB('id =' . $id)->fetchObject(self::class);
    }

    /**
     * Localiza um usuário através do endereço de e-mail.
     * Utilizado principalmente no fluxo de login.
     * @param string $email
     * @return Usuario|false Instância do usuário ou falso se não encontrado.
     */
    public static function getUsuariobyEmail($email)
    {
        return (new Database('tbUsuarios'))->selectDB('email = "' . $email . '"')->fetchObject(self::class);
    }
}
