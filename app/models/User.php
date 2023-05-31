<?php

namespace App\models;

use App\core\Database;

class User
{
  /**
   * ID do usuário
   * @var integer
   */
  public $id;

  /**
   * Nome do usuário
   * @var integer
   */
  public $name;

  /**
   * E-mail do usuário
   * @var string
   */
  public $email;

  /**
   * Senha do usuário
   * @var string
   */
  public $password;

  /**
   * Verifica se o usuário é um administrator
   * @var boolean
   */
  public $is_admin;

  /**
   * ID de quem criou o usuário
   * @var integer
   */
  public $creator_id;

   /**
   * Retorna todos os registros de usuários do banco de dados
   * @return array
   */
  public static function getUsers()
  {
    $query = "SELECT * FROM users";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna um registro de usuário do banco de dados com base em seu email
   * @param string $email
   * @return User
   */
  public static function getUserByEmail($email)
  {
    $query = "SELECT * FROM users WHERE email = ?";

    return Database::execute($query, [$email])->fetchObject(self::class);
  }
}
