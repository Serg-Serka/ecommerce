<?php

class Database
{
    private string $dbUser;
    private string $dbUserPassword;
    private string $dbName;
    private string $dbHost;

    private PDO $connection;

    protected string $mysqlError;

    private static Database $instance;

    private function __construct(
        $dbHost,
        $dbUser,
        $dbUserPassword,
        $dbName
    ) {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbUserPassword = $dbUserPassword;
        $this->dbName = $dbName;
    }

    private function __clone() {}

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Database
    {
        $class = static::class;
        if (!isset(self::$instance)) {
            $config = require 'config.php';
            self::$instance = new static(
                $config['db']['host'],
                $config['db']['username'],
                $config['db']['password'],
                $config['db']['dbname']
            );
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        if (!isset($this->connection)) {
            try {
                $connection = new PDO("mysql:host=$this->dbHost;dbName=$this->dbName", $this->dbUser, $this->dbUserPassword);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection = $connection;
                $this->mysqlError = '';
            } catch (PDOException $exception) {
                $this->mysqlError = $exception->getMessage();
            }
        }

        return $this->connection;
    }

    public function performQuery($sql): array
    {
        $result = [];
        try {
            $connection = $this->getConnection();
            $connection->exec("USE $this->dbName;");
            $query = $connection->query($sql, PDO::FETCH_ASSOC);
            foreach ($query as $row) {
                $result[] = $row;
            }
        } catch (PDOException $exception) {
            $result = [$exception->getMessage()];
        }

        return $result;
    }





}