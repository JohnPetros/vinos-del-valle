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
     * Nome da tabela a ser manipulada
     * @var string
     */
    private static $table;

    /**
     * Instancia de conexão com o banco de dados
     * @var PDO
     */
    private static $connection;

    /**
     * Método responsável por configurar a classe
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


}
?>