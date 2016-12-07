<?php

namespace Db;

class Database
{
    private $_host = 'localhost';
    private $_login = 'root';
    private $_pw = '';
    private $_db = 'contactbeheer';
    private $_conn;

    /*
	 * Extra variables that are required by other function such as boolean con variable
	 */
    private $result = array(); // Any results from a query will be stored here
    private $myQuery = "";// used for debugging process with SQL return
    private $numResults = "";// used for returning the number of rows

    public function connect()
    {

        $this->_conn = new \mysqli($this->_host, $this->_login, $this->_pw, $this->_db);
        // Check connection
        if ($this->_conn->connect_error) {
            echo $this->_conn->connect_error;
        }
    }

//    https://github.com/rorystandley/MySQLi-CRUD-PHP-OOP/blob/master/class/mysql_crud.php
    private function tableExists($table){
        $tablesInDb = $this->_conn->query('SHOW TABLES FROM '.$this->_db.' LIKE "'.$table.'"');
        if($tablesInDb){
            if($tablesInDb->num_rows == 1){
                return true; // The table exists
            }else{
                array_push($this->result,$table." does not exist in this database");
                return false; // The table does not exist
            }
        }
    }


    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
    {
        // Create query from the variables passed to the function
        $q = 'SELECT ' . $rows . ' FROM ' . $table;
        if ($join != null) {
            $q .= ' JOIN ' . $join;
        }
        if ($where != null) {
            $q .= ' WHERE ' . $where;
        }
        if ($order != null) {
            $q .= ' ORDER BY ' . $order;
        }
        if ($limit != null) {
            $q .= ' LIMIT ' . $limit;
        }
        // echo $table;
        $this->myQuery = $q; // Pass back the SQL
        // Check to see if the table exists
        if ($this->tableExists($table)) {
            // The table exists, run the query
            $query = $this->_conn->query($q);
            if ($query) {
                // If the query returns >= 1 assign the number of rows to numResults
                $this->numResults = $query->num_rows;
                // Loop through the query results by the number of rows returned
                for ($i = 0; $i < $this->numResults; $i++) {
                    $r = $query->fetch_array();
                    $key = array_keys($r);
                    for ($x = 0; $x < count($key); $x++) {
                        // Sanitizes keys so only alphavalues are allowed
                        if (!is_int($key[$x])) {
                            if ($query->num_rows >= 1) {
                                $this->result[$i][$key[$x]] = $r[$key[$x]];
                            } else {
                                $this->result[$i][$key[$x]] = null;
                            }
                        }
                    }
                }
                return true; // Query was successful
                return $this->result;
            } else {
                array_push($this->result, $this->_conn->error);
                return false; // No rows where returned
            }
        } else {
            return false; // Table does not exist
        }
    }

    public function insert($table,$params=array()){
        // Check to see if the table exists
        if($this->tableExists($table)){
            $sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES ("' . implode('", "', $params) . '")';
            $this->myQuery = $sql; // Pass back the SQL
            // Make the query to insert to the database
            if($ins = $this->_conn->query($sql)){
                array_push($this->result,$this->_conn->insert_id);
                return true; // The data has been inserted
            }else{
                array_push($this->result,$this->_conn->error);
                return false; // The data has not been inserted
            }
        }else{
            return false; // Table does not exist
        }
    }

    public function getResult(){
        $val = $this->result;
        $this->result = array();
        return $val;
    }


}