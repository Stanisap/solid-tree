<?php

/**
 * This class connections with a database and does queries
 */
namespace application\lib;

use PDO;
use PDOStatement as Statement;

class DB
{
    /**
     * Sets a connect by PDO's library
     * @var PDO|null
     */
    private static $_instance = null;

    /**
     * DB constructor.
     */
    private function __construct()
    {
        $config = require 'application/config/db.php';
        self::$_instance = new PDO(
            'mysql:host='.$config['host'].';dbname='.$config['name'].'',
            $config['user'],
            $config['password'],
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }

    /**
     * Returns self object
     * @return DB|PDO|null
     */
    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        return new self;
    }

    /**
     * Returns a prepared database query
     * @param string $sql the sql query
     * @param array $params these are the parameters to be placed in the query
     * @return bool|Statement
     * @throws Exception else the database query is wrong
     */
    public function query($sql, $params = [])
    {
        $stmt = '';
        try {
            $stmt = self::$_instance->prepare($sql);

            if (!empty($params)) {

                foreach ($params as $key => $val) {
                    if ($val != "")
                        $stmt->bindValue(':' . $key, $val);
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        return $stmt;
    }

    /**
     * Returns all rows by $params
     * @param string $sql the sql query
     * @param array $params these are the parameters to be placed in the query
     * @return array of data from this database
     */
    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a single column from the next row of a result set
     * @param string $sql the sql query
     * @param array $params these are the parameters to be placed in the query
     * @return mixed
     */
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

    /**
     * Returns last insert ID
     * @return string
     */
    public function insertId()
    {
        return self::$_instance->lastInsertId();
    }

    /**
     * Returns a query to the database for adding data in the table
     * @param string $tableName the table's name of the database
     * @param  array $data it's an array of data which to be added in the table
     * @return string the sql query
     */
    public function queryToAdd($tableName, $data)
    {
        $keys1 = [];
        $keys2 = array_keys($data);
        for ($i = 0; $i < count($keys2); $i++) {
            if ($data["$keys2[$i]"] != "") {
                array_push($keys1, $keys2[$i]);
                $keys2[$i] = ':' . $keys2[$i];
            } else {
                unset($keys2[$i]);
            }
        }
        $sql = "INSERT INTO $tableName (";
        $columnName = implode(', ', $keys1);
        $valueName = implode(', ', $keys2);
        $sql = "$sql $columnName) VALUES ( $valueName )";
        return $sql;

    }

    /**
     * Returns a query to the database for getting quantity data in table
     * @param string $tableName the table's name of the database
     * @param string $key
     * @return string the sql query
     */
    public function queryGetRow($tableName, $key)
    {
        $sql = "SELECT COUNT(*) FROM $tableName WHERE ";
        $sql = "$sql $key LIKE :$key";
        return $sql;

    } 

    /**
     * Returns a query for getting all value from the table
     * @param string $tableName the table's name
     * @return array of data for the query
     */
    public function getAll($tableName)
    {
        $sql = "SELECT * FROM $tableName";
        return $this->row($sql);
    }

    /**
     * Return a query for getting all values from the table by ID
     * @param string $tableName the table's name.
     * @return string the sql query
     */
    public function queryById($tableName)
    {
        return "SELECT * FROM $tableName WHERE id = :id";
    }

    /**
     * Removes row by the id in the database
     * @param string $tableName the table's name
     * @param int $id the id of the row
     */
    public function deleteById($tableName, $id)
    {
        $sgl = "DELETE FROM $tableName WHERE id = :id";
        $this->query($sgl, ['id' => $id]);
    }

    /**
     * Clears a table in the database
     * @param string $tableName the table's name
     */
    public function clearTable($tableName)
    {
        $sql = "TRUNCATE TABLE $tableName";
        $this->query($sql);
    }

}