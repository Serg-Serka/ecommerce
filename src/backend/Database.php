<?php

class Database
{
    private $dbUser;
    private $dbUserPassword;
    private $dbName;
    private $dbHost;

    private PDO $connection;

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

    public function getMysqlError()
    {
        return $this->mysqlError;
    }

    public function getAllCategories()
    {
        try {
            $connection = $this->getConnection();
            $connection->exec("USE ecommerce;");
            $sql = "SELECT * FROM categories;";
            $query = $connection->query($sql);
            foreach ($query as $category) {
                $result[$category['id']] = $category['name'];
            }
        } catch (PDOException $exception) {
            $result = $exception->getMessage();;
        }

        return $result;
    }

    public function getProductsByCategoryId($categoryId)
    {

    }

    public function getProductById($productId)
    {

    }




}