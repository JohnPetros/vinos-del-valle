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
   * Avatar do usuário
   * @var string
   */
  public $avatar;

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
   * Adiciona um registro de usuário com os dados da instância atual 
   */
  public function add()
  {
    $query = "INSERT INTO users (
                name,
                email,
                password,
                avatar,
                is_admin,
                creator_id
              ) VALUES (?, ?, ?, ?, ?, ?)";

    $params = [
      $this->name,
      $this->email,
      $this->password,
      $this->avatar,
      $this->is_admin,
      $this->creator_id,
    ];

    Database::execute($query, $params);
  }

  /**
   * Atualiza um registro de usuário com os dados da instância atual 
   */
  public function update()
  {
    $query = "UPDATE users 
              SET name = ?,
                email = ?,
                password = ?,
                avatar = ?,
                is_admin = ?,
                creator_id = ?
              WHERE id = ?";

    $params = [
      $this->name,
      $this->email,
      $this->password,
      $this->avatar,
      $this->is_admin,
      $this->creator_id,
      $this->id,
    ];

    Database::execute($query, $params);
  }

  /**
   * Deleta um registro de usuário com base no ID da instância atual
   */
  public function delete()
  {
    $query = "DELETE FROM users WHERE id = ?";

    Database::execute($query, [$this->id]);
  }

  /**
   * Retorna todos os registros de usuário
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

    if (
      count($params) &&
      isset($params['user-type']) &&
      is_numeric($params['user-type']) &&
      in_array($params['user-type'], [0, 1])
    ) {
      $query .= " AND U.is_admin = ?";
      $queryParams[] = $params['user-type'];
    }

    return Database::execute($query, $queryParams)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna um registro de usuário com base em seu email
   * @param string $email
   * @return User
   */
  public static function getUserByEmail($email)
  {
    $query = "SELECT * FROM users WHERE email = ?";

    return Database::execute($query, [$email])->fetchObject(self::class);
  }

  /**
   * Retorna um registro de usuário com base em seu ID
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
   * Retorna todos os registros de usuário que são administradores
   * @param integer $currentUserId
   * @return array
   */
  public static function getAdminUsers($currentUserId)
  {
    $query = "SELECT id, name 
              FROM users
              WHERE is_admin = 1 AND id != $currentUserId";

    return Database::execute($query)->fetchAll(\PDO::FETCH_CLASS, self::class);
  }

  /**
   * Retorna a quantidade de registros de usuário
   * @return integer
   */
  public static function getUsersAmount()
  {
    $query = "SELECT COUNT(*) FROM users";

    return Database::execute($query)->fetchColumn();
  }
}
