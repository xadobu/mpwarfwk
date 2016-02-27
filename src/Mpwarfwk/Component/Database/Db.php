<?php

namespace Mpwarfwk\Component\Database;


use PDO;

class Db
{
    private $connection;

    public function __construct($config)
    {
        $this->connection = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['username'], $config['password']);
    }

    public function execute($statement, array $params = array())
    {
        $stmt = $this->connection->prepare($statement);
        return $stmt->execute($params);
    }

}