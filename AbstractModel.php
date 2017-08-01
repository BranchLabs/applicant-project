<?php

/**
 * User: justinpratt
 * Date: 7/31/17
 * Time: 5:09 PM
 */
abstract class AbstractModel
{
    protected $_table;
    protected $_pk;

    //data on the object
    private $_data;

    //db connection
    private $_pdo;

    public function __construct()
    {
        //if this were a real production ORM, we would have these in the env file and a separate connection class
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=applicant', 'root', 'root');
        $this->_pdo = $pdo;
    }

    /**
     * Save or update the record
     */
    public function save()
    {
        //if the id is set, we are updating an existing record, otherwise its a new record
        $fields = '';
        if (isset($this->_data[$this->_pk])) {

            foreach ($this->_data as $key => $val) {
                $fields = $fields . $key . " = '" . $val . "',";
            }
            $fields = rtrim($fields, ",");
            $str = "UPDATE " . $this->_table . " SET " . $fields . " WHERE " . $this->_pk . " = " . $this->_data[$this->_pk];
            $this->_pdo->exec($str);
        } else {
            $keys = '';
            foreach ($this->_data as $key => $val) {
                $keys = $keys . $key . ", ";
                $fields = $fields . "'" . $val . "',";
            }
            $keys = rtrim($keys, ", ");
            $fields = rtrim($fields, ",");
            $str = "INSERT INTO " . $this->_table . "(" . $keys . ") VALUES(" . $fields . ")";
            $this->_pdo->exec($str);
            $id = $this->_pdo->lastInsertId();
            $this->load($id);
        }
    }

    /**
     * @param int $id
     * @return $this|bool
     */
    public function load($id)
    {
        // lets grab the record by its id
        if (!$id) {
            return false;
        }
        $query = $this->_pdo->prepare("SELECT * FROM " . $this->_table . " WHERE " . $this->_pk . " = " . $id . " LIMIT 1");
        $query->execute();
        $this->_data = $query->fetch(PDO::FETCH_ASSOC);
        return $this;
    }

    /**
     * @param $id
     */
    public function delete($id = false)
    {
        //delete record by its id
        if (!$id) {
            $id = $this->_data[$this->_pk];
        }
        $str = "DELETE FROM " . $this->_table . " WHERE " . $this->_pk . " = " . $id;
        $this->_pdo->exec($str);
    }

    /**
     * @param bool $key
     * @return mixed
     */
    public function getData($key = false)
    {
        //gets data of the object. if key is set, just get one attribute
        if (!$key) {
            return $this->_data;
        }
        return $this->_data[$key];
    }

    /**
     * @param $arr
     * @param bool $value
     * @return $this
     */
    public function setData($arr, $value = false)
    {
        //set data on the object
        if (!$value) {
            //this is sent an array and set the id if it exists
            foreach($arr as $key => $val) {
                $this->_data[$key] = $val;
            }
        } else {
            //just setting a single value
            $this->_data[$arr] = $value;
        }
        return $this;
    }
}