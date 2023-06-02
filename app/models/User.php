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
   * Nome do criador do usuário
   * @var integer
   */
  public $creator_name;

  /**
   * Retorna todos os registros de usuários do banco de dados
   * @param integer $loggedUserId
   * @param array $params
   * @return array
   */
  public static function getUsers($loggedUserId, $params)
  {
    $query = "SELECT U.*, UC.name AS creator_name
              FROM users AS U
              JOIN users AS UC ON UC.id = U.creator_id 
              WHERE U.id != ?";

    $queryParams = [$loggedUserId];

    if (count($params)) {
      $query .= " AND U.is_admin = ?";
      $queryParams[] = $params['user-type'];
    }

    return Database::execute($query, $queryParams)->fetchAll(\PDO::FETCH_CLASS, self::class);
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

  /**
   * Retorna um registro de usuário do banco de dados com base em seu ID
   * @return User
   */
  public static function getUserById($id)
  {
    $query = "SELECT U.*, UC.id AS creator_id
              FROM users AS U
              JOIN users AS UC ON UC.id = U.creator_id 
              WHERE U.id = ?";

    return Database::execute($query, [$id])->fetchObject(self::class);
  }

  /**
   * Retorna do banco de dados todos os registros de usuários administradores
   * @return array
   */
  public static function getAdminUsers()
  {
    $query = "SELECT id, name 
              FROM users
              WHERE is_admin = 1";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }
}
