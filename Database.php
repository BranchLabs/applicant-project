<?php

require_once './Config.php';

/*
 * BranchLabs Applicant Project
 */

/**
 * Description of Database
 *
 * @author Awoyo Oluwatoyin
 */
abstract class Database
{
    /**
     * An instance of the db
     *
     * @var PDO 
     */
    protected static $_db;
    
    /**
     * Holds a reference to the last inserted record id
     *
     * @var integer 
     */
    public static $_lastInsertId = 0;

    /**
     * Gets an instance of the db
     * 
     * @return PDO
     */
    protected static function getDb()
    {
        if (!static::$_db) {
            static::_initDb();
        }
        
        return static::$_db;
    }
    
    /**
     * Initializes a db connection
     * 
     * @throws Exception
     */
    protected static function _initDb()
    {
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s', Config::DB_HOST, Config::DB_NAME);
            static::$_db = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
        } catch (PDOException $e) {
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }
    
    /**
     * Prepares a SQL query using PDO
     *
     * @param string $query The query string
     * @return string
     */
    protected static function prepare($query)
    {
        $db = static::getDb();
        $stmt = $db->prepare($query);
        return $stmt;
    }
    
    /**
     * Performs database write action
     *
     * @param string $query
     * @param array $params
     * @return boolean
     * @throws Exception
     */
    public static function write($query, $params = [])
    {
        $stmt = static::prepare($query);
        $result = $stmt->execute($params);
        
        if (!$result) {
            throw new Exception('An error occured during save');
        }
        
        static::$_lastInsertId = static::getDb()->lastInsertId();
        return $result;
    }
 
    /**
     * Performs a select statement
     *
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function load($query, $params = [])
    {
        $stmt = static::prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
