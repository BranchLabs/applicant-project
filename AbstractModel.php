<?php

require_once './Database.php';

/*
 * BranchLabs Applicant Project
 */

/**
 * Description of AbstractModel
 *
 * @author Awoyo Oluwatoyin
 */
abstract class AbstractModel implements ArrayAccess
{
    /**
     * Main table
     *
     * @var string
     */
    protected $_table;
    
    /**
     * Main table primary key
     *
     * @var string
     */
    protected $_pk;
    
    /**
     * Holds object attributes
     *
     * @var array
     */
    protected $_data    = [];
    
    /**
     * Save object
     */
    public function save()
    {
        if (isset($this->_data[$this->_pk])) {
            return $this->_update();
        }
        return $this->_insert();
    }
    
    /**
     * Loads an object record
     * 
     * @param integer $id
     */
    public function load($id)
    {
        if (!$id) {
            throw new Exception('A valid record Id is required to perform this operation');
        }
 
        $query = "SELECT * FROM %s WHERE %s=?";
        $data = Database::load(sprintf($query, $this->_table, $this->_pk), [$id]);
        if ($data) {
            $this->_data = $data;
        }

        return $this;
    }
    
    /**
     * Deletes an object record
     * 
     * @param string|null $id
     * @return AbstractModel
     * @throws Exception
     */
    public function delete($id=null)
    {
        $_pk = ($id) ? $id : $this->_data[$this->_pk];
        
        if (!$_pk) {
            throw new Exception('You must either provide an id or call this method on an object instance');
        }
        
        $query = "DELETE FROM `%s` WHERE `%s`=%d";
        $prepared = sprintf($query, $this->_table, $this->_pk, $_pk);

        Database::write($prepared);
        
        $this->_data = [];
        return $this->_data;
    }
    
    /**
     * Performs record insert
     * 
     * @return AbstractModel
     */
    protected function _insert()
    {
        $query = "INSERT INTO `%s` (%s) VALUES (%s)";
        $params = $this->_formatDataForSave();
        
        $parsed = sprintf($query, $this->_table, implode(",", $params['fields']), implode(",", $params['binds']));
        Database::write($parsed, $params['insert']);
        $lastInsertId = Database::$_lastInsertId;
        
        return $this->load((int) $lastInsertId);
    }
    
    /**
     * Performs record update
     * 
     * @return AbstractModel
     */
    protected function _update()
    {
        $query = "UPDATE `%s` SET %s WHERE `%s`=%d";
        $params = $this->_formatDataForSave();
        
        $prepared = sprintf($query, $this->_table, implode(", ", $params['update']), $this->_pk, $this->_data[$this->_pk]);

        Database::write($prepared, $params['insert']);
        
        return $this->load($this->_data[$this->_pk]);
    }

    /**
     * Retrieves data from the object
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     *
     * @param string $key
     * @return mixed
     */
    public function getData($key=false)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : $this->_data;
    }
    
    /**
     * Overwrite data in the object.
     *
     * $arr can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     * 
     * @param string|array $arr
     * @param mixed $value
     * @return $this
     * @throws Exception
     */
    public function setData($arr, $value=false)
    {
        if(is_array($arr)) {
            $this->_data = $arr;
        } elseif ($arr && $value) {
            $this->_data[$arr] = $value;
        } else {
            throw new Exception('Invalid data supplied');
        }
        
        return $this;
    }

    /**
     * Implementation of ArrayAccess::offsetSet()
     * 
     * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
     * @param type $offset
     * @param type $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$offset] = $value;
        }
    }
    
    /**
     * Implementation of ArrayAccess::offsetExists()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
     * @param type $offset
     * @return type
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }
    
    /**
     * Implementation of ArrayAccess::offsetUnset()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetunset.php
     * @param type $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }
    
    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetset.php
     * @param type $offset
     * @return type
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }
    
    /**
     * Prepares|Re-formats the query parameters
     * 
     * @return array
     */
    protected function _formatDataForSave()
    {
        $params = [
            'fields'    => [],
            'binds'     => [],
            'update'    => [],
            'insert'    => []
        ];
        
        foreach ($this->_data as $key => $value) {
            $params['binds'][] = ":{$key}";
            $params['fields'][] = "`{$key}`";
            $params['update'][] = "`{$key}` = :{$key}";
            $params['insert'][$key] = $value;
        }
        
        return $params;
    }
}
