<?php

abstract class AbstractModel {
    
    protected $_table;
    protected $_pk;
    protected $db;
    protected $fields = array();
    
    public function __construct() {
        
        $db_options = get_option('blo_options');
        $db_host = $db_options['db_host'];
        $db_name = $db_options['db_name'];
        $db_user = $db_options['db_user'];
        $db_password = $db_options['db_password'];
        try {
            $this->db = new \PDO("mysql:host={$db_host};dbname={$db_name}",$db_user, $db_password);
        } catch (\Exception $e) {
            throw new \Exception('Error creating a database connection ');
        }
        
    }
    
    public function save(){
        
        foreach ($this->fields as $key => $value){
            $inserts[] = ':' . $key;
            $updates[] = $key . "= :u_" . $key;
        }
        
        //Prepare fields to update or insert, depending on the case
        $insert_vals = implode(',', $inserts);
        $insert_cols = implode(',', array_keys($this->fields));
        $update_q = implode(',', $updates);        
        
        $sql = "INSERT INTO $this->_table ($insert_cols) VALUES ($insert_vals) ON DUPLICATE KEY UPDATE $update_q";
        $query = $this->db->prepare($sql);
        
        //Bind values. Different name for updates as bound names can't be repeated.
        foreach ($this->fields as $key => $value)
        {
            $query->bindValue(':' . $key, $value);
            $query->bindValue(':u_' . $key, $value);
        }
        
        $query->execute();
        
        //If operation was INSERT, update fields with new PK at the beginning
        if (!array_key_exists($this->_pk, $this->fields)) {
            $this->fields = array_merge(
                array(
                    $this->_pk => $this->db->lastInsertId()
                ),
                $this->fields);
            }
            
        }
        
        public function load($id){
            $sql = "SELECT * FROM $this->_table WHERE $this->_pk = ? LIMIT 1";
            $query = $this->db->prepare($sql);
            
            
            $query->execute(array($id));
            
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                foreach ($result as $key=>$value) {
                    $this->fields[$key] = $value;
                }
            }
            
            return $this;
        }
        
        public function delete($id=false){
            $sql = "DELETE FROM $this->_table WHERE $this->_pk = ?";
            
            $query = $this->db->prepare($sql);
            if (array_key_exists($this->_pk, $this->fields)) {
                $toDelete = $this->fields[$this->_pk];
            } else {
                $toDelete = $id;
            }
            
            $query->execute(array($toDelete));
        }
        
        public function getData($key=false){
            
            if ($key) {
                return $this->fields[$key];
            }
            
            $toString = "<pre>";
            foreach($this->fields as $k => $value) {
                $toString .= "$k => $value" . PHP_EOL;
            }
            $toString .= "</pre>";
            
            return $toString;
        }
        
        public function setData($arr, $value=false){
            if (is_array($arr)) {
                foreach ($arr as $key=>$value) {
                    $this->fields[$key] = $value;
                }
            } else {
                $this->fields[$arr] = $value;
            }
            return $this;
        }
        
    }