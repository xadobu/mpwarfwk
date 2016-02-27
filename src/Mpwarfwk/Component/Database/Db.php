<?php

namespace Mpwarfwk\Component\Database;


use PDO;

class Db
{
    private $connection;
    private $profiler;

    public function __construct($config, DBProfiler $profiler)
    {
        $this->connection = new PDO("mysql:host={$config['host']};dbname={$config['db_name']}", $config['username'], $config['password']);
        $this->profiler = $profiler;
    }

    public function execute($statement, array $params = array())
    {
        $this->profiler->startTime();
        $stmt = $this->connection->prepare($statement);
        $result = $stmt->execute($params);
        $this->profiler->endTime();
        return $result;
    }

}