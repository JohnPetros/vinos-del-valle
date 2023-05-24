<?php

namespace App\core;

use \PDO;
use \PDOException;

class Database
{
    /**
     * Host de conexão com o banco de dados
     * @var string
     */
    private static $host;

    /**
     * Nome do banco de dados
     * @var string
     */
    private static $name;

    /**
     * Usuário do banco de dados
     * @var string
     */
    private static $user;

    /**
     * Senha de acesso ao banco de dados
     * @var string
     */
    private static $pass;

    /**
     * Porta de acesso ao banco
     * @var string
     */
    private static $port;


    /**
     * Instância de conexão com o banco de dados
     * @var PDO
     */
    private static $connection;

    /**
     * Configura a classe
     * @param string $host
     * @param string $name
     * @param string $user
     * @param string $pass
     * @param string $port
     */
    public static function config($host, $name, $user, $pass, $port = 3306)
    {
        self::$host = $host;
        self::$name = $name;
        self::$user = $user;
        self::$pass = $pass;
        self::$port = $port;
    }

    /**
     * Cria uma conexão com o banco de dados
     */
    private static function setConnection()
    {
        try {
            self::$connection = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$name . ';port=' . self::$port, self::$user, self::$pass);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Executa as queries dentro do banco de dados
     * @param  string $query
     * @param  array  $params
     * @return @return PDOStatement
     */
    public static function execute($query, $params = [])
    {
        self::setConnection();

        try {
            $statement = self::$connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }
}
