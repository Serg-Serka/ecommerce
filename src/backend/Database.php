<?php

class Database
{
    private $dbUser;
    private $dbUserPassword;
    private $dbName;
    private $dbHost;

    private $connection;

    protected $mysqlError;

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

    public function connect(): string
    {
        $success = false;
        if (!isset($this->connection)) {
            try {
                $connection = new PDO("mysql:host=$this->dbHost;dbName=$this->dbName", $this->dbUser, $this->dbUserPassword);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection = $connection;
                $this->mysqlError = '';
                $success = true;
            } catch (PDOException $exception) {
                $this->mysqlError = $exception->getMessage();
//                $success = false;
            }
        }

        return $success;
    }

    public function getMysqlError()
    {
        return $this->mysqlError;
    }

    public function getAllCategories()
    {

    }

    public function getProductsByCategoryId($categoryId)
    {

    }

    public function getProductById($productId)
    {

    }




}